<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\Course;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClassRoomStudentsExport;
use App\Http\Requests\ClassRoomStoreRequest;
use App\Http\Requests\ClassRoomUpdateRequest;
use App\Imports\ClassRoomStudentsImport;
use Exception;
use Illuminate\Support\Facades\Validator;

class ClassRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ClassRoom::with('academicYear')
            ->withCount('students');

        // Search filter
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('code', 'LIKE', "%{$request->search}%");
            });
        }

        // Level filter
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        // Academic Year filter
        if ($request->filled('academic_year')) {
            $query->where('academic_year_id', $request->academic_year);
        }

        $classRooms = $query->paginate(10);
        $academicYears = AcademicYear::all();

        return view('classRoom.index', compact('classRooms', 'academicYears'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $academicYears = AcademicYear::all();
        return view('classRoom.create', compact('academicYears'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try{
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string'],
                'code' => ['required', 'string', 'unique:class_rooms,code'],
                'level' => ['required', 'string'],
                'description' => ['nullable', 'string'],
                'capacity' => ['required', 'integer'],
                'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
                'schedule_id' => ['nullable', 'integer', 'exists:schedules,id'],
                'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
                'student_count' => ['required', 'integer'],
            ]);

        // $validatedData = $request->validated();

        $classRoom = ClassRoom::create($validator->validated());
        }catch(Exception $e){
                dd($e);
        }


        return redirect()
            ->route('class-rooms.show', $classRoom)
            ->with('success', 'La classe a été créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $classRoom)
    {
        $classRoom->load('academicYear');

        $studentsCount = $classRoom->students()->count();
        $students = $classRoom->students()->paginate(10);

        $coursesCount = $classRoom->courses()->count();
        $courses = $classRoom->courses()->with('department')->get();

        return view('classRoom.show', compact(
            'classRoom',
            'students',
            'studentsCount',
            'courses',
            'coursesCount'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        $academicYears = AcademicYear::all();
        return view('classRoom.edit', compact('classRoom', 'academicYears'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ClassRoom $classRoom)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'code' => ['required', 'string', "unique:class_rooms,code,$classRoom->id"],
            'level' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'capacity' => ['required', 'integer'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'schedule_id' => ['nullable', 'integer', 'exists:schedules,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'student_count' => ['required', 'integer'],
        ]);

        // $validatedData = $request->validated();

        $classRoom->update(($validator->validated()));

        return redirect()
            ->route('class-rooms.show', $classRoom)
            ->with('success', 'La classe a été mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
    {
        // Optional: Add a check to prevent deletion if students exist
        if ($classRoom->students()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Impossible de supprimer une classe contenant des étudiants.');
        }

        $classRoom->delete();

        return redirect()
            ->route('class-rooms.index')
            ->with('success', 'La classe a été supprimée avec succès.');
    }

    /**
     * Export students of a specific class
     */
    public function exportStudents(ClassRoom $classRoom)
    {
        return Excel::download(
            new ClassRoomStudentsExport($classRoom->id),
            "students_{$classRoom->code}.xlsx"
        );
    }

    /**
     * Import students for a specific class
     */
    public function importStudents(Request $request, ClassRoom $classRoom)
    {
        $request->validate([
            'students_file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(
            new ClassRoomStudentsImport($classRoom->id),
            $request->file('students_file')
        );

        return redirect()
            ->route('class-rooms.show', $classRoom)
            ->with('success', 'Les étudiants ont été importés avec succès.');
    }
}
