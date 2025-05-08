<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentDetailStoreRequest;
use App\Http\Requests\PaymentDetailUpdateRequest;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentDetailController extends Controller
{
    public function index(Request $request): View
    {
        $paymentDetails = PaymentDetail::all();

        return view('paymentDetail.index', compact('paymentDetails'));
    }

    public function create(Request $request): View
    {
        return view('paymentDetail.create');
    }



    public function show(Request $request, PaymentDetail $paymentDetail): View
    {
        return view('paymentDetail.show', compact('paymentDetail'));
    }




    public function store(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'fee_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $payment->paymentDetails()->create($validated);

        // Update total payment amount
        $totalAmount = $payment->paymentDetails()->sum('amount');
        $payment->update(['amount' => $totalAmount]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment detail added successfully.');
    }


    public function edit(PaymentDetail $paymentDetail)
    {
        return view('payment_details.edit', compact('paymentDetail'));
    }

    public function update(Request $request, PaymentDetail $paymentDetail)
    {
        $validated = $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'fee_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $paymentDetail->update($validated);

        // Update total payment amount
        $payment = $paymentDetail->payment;
        $totalAmount = $payment->paymentDetails()->sum('amount');
        $payment->update(['amount' => $totalAmount]);

        return redirect()->route('payments.show', $paymentDetail->payment)
            ->with('success', 'Payment detail updated successfully.');
    }

    public function destroy(PaymentDetail $paymentDetail)
    {
        $payment = $paymentDetail->payment;
        $paymentDetail->delete();

        // Update total payment amount
        $totalAmount = $payment->paymentDetails()->sum('amount');
        $payment->update(['amount' => $totalAmount]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Payment detail deleted successfully.');
    }
}
