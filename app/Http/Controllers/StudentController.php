<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\StudentUpdateRequest;
use App\Models\Student;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::with('academicYear')
            ->when($request->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('matricule', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('student.index', compact('students'));
    }

    public function create(): View
    {
        $student = null;
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $classrooms = ClassRoom::orderBy('name','asc')->get();
        return view('student.create', compact(['academicYears', 'student','classrooms']));
    }

    public function store(StudentStoreRequest $request): RedirectResponse
    {

        $validatedData = $request->validated();


        try {

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'student_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/images/students', $filename);
            $validatedData['photo'] = Storage::url($path);
        }

        // Générer un matricule unique
        $validatedData['matricule'] = $this->generateMatricule();

        // Traiter les informations supplémentaires
        if ($request->has('additional_info')) {
            $validatedData['additional_info'] = json_encode($request->additional_info);
        }

        $student = Student::create($validatedData);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Étudiant ajouté avec succès.');

        } catch (\Exception $e) {
            return redirect()
                ->route('students.create')
                ->with('error', 'Une erreur s\'est produite lors de l\'ajout de l\'étudiant. Veuillez réessayer.'.$e);
        }
    }

    public function show(Student $student): View
    {
        $student->load(['academicYear', 'courseEnrollments.course', 'grades', 'payments']);
        return view('student.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        $classrooms = ClassRoom::orderBy('name','asc')->get();
        return view('student.edit', compact('student', 'academicYears','classrooms'));
    }

    public function update(StudentUpdateRequest $request, Student $student): RedirectResponse
    {

        $validatedData = $request->validated();

        try {
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo
            if ($student->photo) {
                Storage::delete(str_replace('/storage', 'public', $student->photo));
            }

            $photo = $request->file('photo');
            $filename = 'student_' . time() . '.' . $photo->getClientOriginalExtension();
            $path = $photo->storeAs('public/images/students', $filename);
            $validatedData['photo'] = Storage::url($path);
        }

        if ($request->has('additional_info')) {
            $validatedData['additional_info'] = json_encode($request->additional_info);
        }

        $student->update($validatedData);

        return redirect()
            ->route('students.show', $student)
            ->with('success', 'Informations mises à jour avec succès.');

        } catch (\Exception $e) {
            return redirect()
                ->route('students.edit', $student)
                ->with('error', 'Une erreur s\'est produite lors de la mise à jour des informations. Veuillez réessayer.'.$e);
        }
    }

    public function destroy(Student $student): RedirectResponse
    {
        // Supprimer la photo si elle existe
        if ($student->photo) {
            Storage::delete(str_replace('/storage', 'public', $student->photo));
        }

        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Étudiant supprimé avec succès.');
    }

    private function generateMatricule(): string
    {
        do {
            $matricule = date('Y') . Str::padLeft(mt_rand(0, 9999), 4, '0');
        } while (Student::where('matricule', $matricule)->exists());

        return $matricule;
    }
    public function search(Request $request)
    {
        $query = $request->query('query');

        // Validation
        if (empty($query)) {
            return response()->json(['error' => 'Query parameter is required'], 400);
        }

        $students = Student::where('matricule', 'LIKE', "%{$query}%")
                            ->orWhere('first_name', 'LIKE', "%{$query}%")
                            ->orWhere('last_name', 'LIKE', "%{$query}%")
                            ->limit(5)
                            ->get();

        return response()->json($students);
    }


}
