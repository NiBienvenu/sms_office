<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherStoreRequest;
use App\Models\Teacher;
use App\Models\Department;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::with('department');

        // Search filter
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Department filter
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        // Contract type filter
        if ($request->has('contract_type') && $request->contract_type) {
            $query->where('contract_type', $request->contract_type);
        }

        // Employment status filter
        if ($request->has('employment_status') && $request->employment_status) {
            $query->where('employment_status', $request->employment_status);
        }

        $teachers = $query->latest()->paginate(10);
        $departments = Department::all();

        return view('teacher.index', compact('teachers', 'departments'));
    }

    public function create()
    {
        $teacher= null;
        $departments = Department::all();
        return view('teacher.create', compact(['departments','teacher']));
    }

    public function store(Request $request)
    {
        // $validated = $request->validate();
        try {


        $validated = $request->validate([
            'employee_id' => 'required|unique:teachers',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'nationality' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'joining_date' => 'required|date',
            'contract_type' => 'required|string',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
            'salary_grade' => 'required|string',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('teacher-photos', 'public');
            $validated['photo'] = $path;
        }

        Teacher::create($validated);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
        }catch(Exception $e){
            dd($e);
        }
    }

    public function show(Teacher $teacher)
    {
        return view('teacher.show', compact('teacher'));
    }

    public function edit(Teacher $teacher)
    {
        $departments = Department::all();
        return view('teacher.edit', compact('teacher', 'departments'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'employee_id' => 'required|unique:teachers,employee_id,' . $teacher->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'gender' => 'required|string',
            'birth_date' => 'required|date',
            'nationality' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'joining_date' => 'required|date',
            'contract_type' => 'required|string',
            'qualification' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'experience_years' => 'required|integer',
            'department_id' => 'required|exists:departments,id',
            'position' => 'required|string|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:255',
            'salary_grade' => 'required|string',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $path = $request->file('photo')->store('teacher-photos', 'public');
            $validated['photo'] = $path;
        }

        $teacher->update($validated);

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}
