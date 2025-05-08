<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Department::with(['headTeacher', 'academicYear']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%'.request('search').'%')
                    ->orWhere('code', 'like', '%'.request('search').'%');
            });
        }
        // Department filter
        // if ($request->has('academic_year') && $request->academic_year) {
        //     $search = $request->academic_year;
        //     $query->whereHas('academic_year')->where(function($q) use ($search) {
        //         $q->where('year', 'like', '%'.$search.'%');
        //     });
        // }
         if($request->has('status') && $request->status) {
            $query->where('status', request('status'));
         }
            $departments = $query->latest()
            ->paginate(10);
            $academicYears = AcademicYear::latest()->get();

        return view('department.index', compact(['departments', 'academicYears']));
    }

    public function create()
    {
        $teachers = Teacher::all();
        $academicYears = AcademicYear::all();
        return view('department.create', compact('teachers', 'academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'code' => 'required|string|max:50|unique:departments',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'status' => 'required|in:active,inactive',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        $department->load(['headTeacher', 'academicYear']);
        return view('department.show', compact('department'));
    }

    public function edit(Department $department)
    {
        $teachers = Teacher::all();
        $academicYears = AcademicYear::all();
        return view('department.edit', compact('department', 'teachers', 'academicYears'));
    }

    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'code' => 'required|string|max:50|unique:departments,code,'.$department->id,
            'description' => 'nullable|string',
            'head_teacher_id' => 'nullable|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'status' => 'required|in:active,inactive',
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
