<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['department', 'subject'])
            ->when(request('search'), function($query) {
                $query->where('name', 'like', '%'.request('search').'%')
                    ->orWhere('code', 'like', '%'.request('search').'%');
            })
            ->when(request('department'), function($query) {
                $query->where('department_id', request('department'));
            })
            ->when(request('semester'), function($query) {
                $query->where('semester', request('semester'));
            })
            ->when(request('course_type'), function($query) {
                $query->where('course_type', request('course_type'));
            })
            ->latest()
            ->paginate(10);

        $departments = Department::all();

        return view('course.index', compact('courses', 'departments'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $departments = Department::all();
        $teachers = Teacher::all();
        $academicYears = AcademicYear::all();
        $course = null;

        return view('course.create', compact(
            'subjects',
            'departments',
            'teachers',
            'academicYears',
            'course'
        ));
    }

    public function store(Request $request)
    {
        try {
            //code...
            $validated = $request->validate([
                'code' => 'required|string|unique:courses,code|max:50',
                'name' => 'required|string|max:255',
                'subject_id' => 'required|exists:subjects,id',
            'department_id' => 'required|exists:departments,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'hours_per_week' => 'required|integer|min:1',
            'course_type' => 'required|in:Mandatory,Elective',
            'education_level' => 'required|in:Undergraduate,Graduate,Doctorate',
            'semester' => 'required|in:Fall,Spring,Summer',
            'max_students' => 'required|integer|min:1',
            'objectives' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'assessment_method' => 'required',
            // 'assessment_method' => 'required|in:Exam,Project,Mixed',
            'status' => 'nullable|in:active,inactive',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $course = Course::create($validated);

        // Attach teachers if provided
        if (isset($validated['teachers'])) {
            $course->teachers()->sync($validated['teachers']);
        }

        return redirect()->route('courses.index')
        ->with('success', 'Course created successfully.');
        } catch (\Throwable $th) {
            dump($th);
        }
    }

    public function show(Course $course)
    {
        $course->load(['department', 'subject', 'academicYear', 'teachers']);
        return view('course.show', compact('course'));
    }

    public function edit(Course $course)
    {
        $subjects = Subject::all();
        $departments = Department::all();
        $teachers = Teacher::all();
        $academicYears = AcademicYear::all();

        return view('course.edit', compact(
            'course',
            'subjects',
            'departments',
            'teachers',
            'academicYears'
        ));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:courses,code,'.$course->id.'|max:50',
            'name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'department_id' => 'required|exists:departments,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1',
            'hours_per_week' => 'required|integer|min:1',
            'course_type' => 'required|in:Mandatory,Elective',
            'education_level' => 'required|in:Undergraduate,Graduate,Doctorate',
            'semester' => 'required|in:Fall,Spring,Summer',
            'max_students' => 'required|integer|min:1',
            'objectives' => 'nullable|string',
            'syllabus' => 'nullable|string',
            'assessment_method' => 'required|in:Exam,Project,Mixed',
            'status' => 'required|in:active,inactive',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
        ]);

        $course->update($validated);

        // Sync teachers
        if (isset($validated['teachers'])) {
            $course->teachers()->sync($validated['teachers']);
        } else {
            $course->teachers()->detach();
        }

        return redirect()->route('courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully.');
    }
}
