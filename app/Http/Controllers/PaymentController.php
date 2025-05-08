<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{

        public function index(Request $request)
        {
                $query = Payment::with(['student', 'academicYear', 'paymentDetails']);

                if ($request->filled('matricule')) {
                    $query->whereHas('student', function ($q) use ($request) {
                        $q->where('matricule', 'like', '%' . $request->matricule . '%');
                    });
                }
                if ($request->filled('academic_year')) {
                    $query->where('academic_year_id', $request->academic_year);
                }
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('semester')) {
                    $query->where('semester', $request->semester);
                }

                $payments = $query->paginate(10);
                $academicYears = AcademicYear::all();

                return view('payment.index', compact('payments', 'academicYears'));


    }

    public function create()
    {
        $students = Student::all();
        $academicYears = AcademicYear::all();
        $paymentTypes = ['Cash', 'Bank Transfer', 'Check', 'Mobile Money'];
        $semesters = ['First', 'Second'];
        $statuses = ['pending', 'completed', 'cancelled'];

        return view('payment.create', compact(
            'students',
            'academicYears',
            'paymentTypes',
            'semesters',
            'statuses'
        ));
    }

    public function store(Request $request)
    {
        try {
            //code...
            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'academic_year_id' => 'required|exists:academic_years,id',
                'amount' => 'required|numeric|min:0',
                'payment_type' => 'required|string',
                'payment_date' => 'required|date',
                'status' => 'required|in:pending,completed,cancelled',
                'semester' => 'required|string',
                'payment_details' => 'required|array|min:1',
                'payment_details.*.fee_type' => 'required|string',
                'payment_details.*.amount' => 'required|numeric|min:0',
                'payment_details.*.description' => 'nullable|string',
            ]);

            // Generate unique reference number
            $validated['reference_number'] = 'PAY-' . Str::random(10);

            $payment = Payment::create($validated);

            // Create payment details
            foreach ($request->payment_details as $detail) {
                $payment->paymentDetails()->create($detail);
            }

            return redirect()->route('payments.show', $payment)
                ->with('success', 'Payment created successfully.');
        } catch (\Throwable $th) {
            dump($th);
        }

    }

    public function show(Payment $payment)
    {
        $payment->load(['student', 'academicYear', 'paymentDetails']);
        return view('payment.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $students = Student::all();
        $academicYears = AcademicYear::all();
        $paymentTypes = ['Cash', 'Bank Transfer', 'Check', 'Mobile Money'];
        $semesters = ['First', 'Second'];
        $statuses = ['pending', 'completed', 'cancelled'];

        return view('payments.edit', compact(
            'payment',
            'students',
            'academicYears',
            'paymentTypes',
            'semesters',
            'statuses'
        ));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string',
            'payment_date' => 'required|date',
            'status' => 'required|in:pending,completed,cancelled',
            'semester' => 'required|string',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
    public function detailForm(Request $request)
    {
        $index = $request->query('index', 0); // Récupère l'index actuel des détails
        return view('paymentDetail._form', compact('index'))->render();
    }

}
