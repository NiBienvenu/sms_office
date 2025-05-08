<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Department;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with(['department', 'academicYear', 'courses'])
            ->when($request->search, function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            })
            ->when($request->department, function($q) use ($request) {
                $q->where('department_id', $request->department);
            })
            ->when($request->status, function($q) use ($request) {
                $q->where('status', $request->status);
            });

        $subjects = $query->latest()->paginate(10);
        $departments = Department::all();

        return view('subject.index', compact('subjects', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $academicYears = AcademicYear::all();
        return view('subject.create', compact('departments', 'academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:subjects,code',
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        Subject::create($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject created successfully');
    }

    public function show(Subject $subject)
    {
        $subject->load(['department', 'academicYear', 'courses']);
        return view('subject.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $departments = Department::all();
        $academicYears = AcademicYear::all();
        return view('subject.edit', compact('subject', 'departments', 'academicYears'));
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:subjects,code,' . $subject->id,
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive'
        ]);

        $validated['status'] = $request->has('status') ? 'active' : 'inactive';

        $subject->update($validated);

        return redirect()
            ->route('subjects.index')
            ->with('success', 'Subject updated successfully');
    }

    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
            return redirect()
                ->route('subjects.index')
                ->with('success', 'Subject deleted successfully');
        } catch (\Exception $e) {
            return redirect()
                ->route('subjects.index')
                ->with('error', 'Unable to delete subject. It may have associated records.');
        }
    }
}
