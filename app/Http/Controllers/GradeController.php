<?php

namespace App\Http\Controllers;

use App\Exports\GradesExport;
use App\Http\Requests\GradeStoreRequest;
use App\Http\Requests\GradeUpdateRequest;
use App\Imports\GradesImport;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class GradeController extends Controller
{
    /**
     * Display a listing of the student grades.
     */
    public function index(Request $request)
    {
        $query = Grade::with(['student', 'course', 'teacher', 'academicYear']);

        // Filtres
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('trimester')) {
            $query->where('trimester', $request->trimester);
        }

        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_room_id', $request->class_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('matricule', 'like', "%{$search}%");
            });
        }

        $grades = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calcul du pourcentage pour chaque note
        foreach ($grades as $grade) {
            $grade->percentage = ($grade->score / $grade->max_score) * 100;
        }

        // Données pour les filtres
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $courses = Course::orderBy('name')->get();
        $teachers = Teacher::orderBy('last_name')->get();
        $classes = ClassRoom::orderBy('name')->get();

        return view('grade.index', compact('grades', 'academicYears', 'courses', 'teachers', 'classes'));
    }

    /**
     * Show the form for creating a new student grade.
     */
    public function create()
    {
        $students = Student::orderBy('last_name')->get();
        $courses = Course::orderBy('name')->get();
        $teachers = Teacher::orderBy('last_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('grade.create', compact('students', 'courses', 'teachers', 'academicYears'));
    }

    /**
     * Store a newly created student grade in storage.
     */
    public function store(Request $request)
    {
        try {
            //code...
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|exists:students,id',
                'course_id' => 'required|exists:courses,id',
                'teacher_id' => 'required|exists:teachers,id',
                'academic_year_id' => 'nullable|exists:academic_years,id',
                'trimester' => 'required|in:1,2,3',
                'grade_type' => 'required|string',
                'score' => 'required|numeric|min:0',
                'max_score' => 'required|numeric|min:1',
                'comment' => 'nullable|string',
            ]);

            if ($validator->fails()) {

                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Vérifier si la note n'existe pas déjà
            $exists = Grade::where('student_id', $request->student_id)
                          ->where('course_id', $request->course_id)
                          ->where('trimester', $request->trimester)
                          ->where('grade_type', $request->grade_type)
                          ->where('academic_year_id', $request->academic_year_id)
                          ->exists();

            if ($exists) {
                return redirect()->back()
                    ->with('error', 'Une note de ce type existe déjà pour cet élève dans ce cours et ce trimestre.')
                    ->withInput();
            }

            Grade::create($request->all());

            return redirect()->route('grades.index')
                ->with('success', 'La note a été enregistrée avec succès.');
        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }

    }

    /**
     * Display the specified student grade.
     */
    public function show(Grade $grade)
    {
        $grade->percentage = ($grade->score / $grade->max_score) * 100;
        return view('grade.show', compact('grade'));
    }

    /**
     * Show the form for editing the specified student grade.
     */
    public function edit(Grade $grade)
    {
        $students = Student::orderBy('last_name')->get();
        $courses = Course::orderBy('name')->get();
        $teachers = Teacher::orderBy('last_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('grade.edit', compact('grade', 'students', 'courses', 'teachers', 'academicYears'));
    }

    /**
     * Update the specified student grade in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'trimester' => 'required|in:1,2,3',
            'grade_type' => 'required|string',
            'score' => 'required|numeric|min:0',
            'max_score' => 'required|numeric|min:1',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que la note ne dépasse pas le maximum
        if ($request->score > $request->max_score) {
            return redirect()->back()
                ->with('error', 'La note ne peut pas dépasser le maximum défini.')
                ->withInput();
        }

        $grade->update($request->all());

        return redirect()->route('grades.index')
            ->with('success', 'La note a été mise à jour avec succès.');
    }

    /**
     * Remove the specified student grade from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', 'La note a été supprimée avec succès.');
    }



    /**
     * Show the bulk entry form.
     */
    public function bulkEntryForm()
    {
        $courses = Course::orderBy('name')->get();
        $teachers = Teacher::orderBy('last_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $classes = ClassRoom::orderBy('name')->get();

        return view('grade.bulk_entry', compact('courses', 'teachers', 'academicYears', 'classes'));
    }

    /**
     * Get students by class for the bulk entry form.
     */
    public function getStudentsByClass(Request $request)
    {
        $class_id = $request->classid;
        $year_id = $request->yearid;
        $students = Student::where('class_room_id', $class_id)
                            ->where('academic_year_id',$year_id)
                           ->orderBy('last_name')
                           ->get();

        return response()->json($students);
    }

    /**
     * Store multiple grades at once.
     */
    public function storeBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'trimester' => 'required|in:1,2,3',
            'grade_type' => 'required|string',
            'max_score' => 'required|numeric|min:1',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.score' => 'required|numeric|min:0',
            'grades.*.comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            foreach ($request->grades as $grade) {
                // Vérifier si la note ne dépasse pas le maximum
                if ($grade['score'] > $request->max_score) {
                    throw new \Exception("La note pour l'étudiant ID {$grade['student_id']} ne peut pas dépasser le maximum défini.");
                }

                // Vérifier si une note existe déjà pour cet étudiant
                $exists = Grade::where('student_id', $grade['student_id'])
                              ->where('course_id', $request->course_id)
                              ->where('trimester', $request->trimester)
                              ->where('grade_type', $request->grade_type)
                              ->where('academic_year_id', $request->academic_year_id)
                              ->exists();

                if ($exists) {
                    $student = Student::find($grade['student_id']);
                    throw new \Exception("Une note de ce type existe déjà pour l'élève {$student->last_name} {$student->first_name}.");
                }

                Grade::create([
                    'student_id' => $grade['student_id'],
                    'course_id' => $request->course_id,
                    'teacher_id' => $request->teacher_id,
                    'academic_year_id' => $request->academic_year_id,
                    'trimester' => $request->trimester,
                    'grade_type' => $request->grade_type,
                    'score' => $grade['score'],
                    'max_score' => $request->max_score,
                    'comment' => $grade['comment'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('grades.index')
                ->with('success', 'Les notes ont été enregistrées avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Une erreur est survenue: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher le formulaire de recherche pour générer un bulletin
     */
    public function reportCardSearch()
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $classes = ClassRoom::orderBy('name')->get();

        return view('grade.report_card_search', compact('academicYears', 'classes'));
    }

    /**
     * Afficher la liste des étudiants pour sélectionner un bulletin
     */
    public function reportCardList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'trimester' => 'required',
            'class_id' => 'required|exists:class_rooms,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $students = Student::where('class_room_id', $request->class_id)
                           ->orderBy('last_name')
                           ->get();

        $academicYear = AcademicYear::find($request->academic_year_id);
        $class = ClassRoom::find($request->class_id);
        $trimester = $request->trimester;

        return view('grade.report_card_list', compact('students', 'academicYear', 'class', 'trimester'));
    }

    /**
     * Générer le bulletin PDF pour un étudiant
     */
    public function generateReportCard(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'trimester' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $academicYear = AcademicYear::find($request->academic_year_id);
        $trimester = $request->trimester;

        // Récupérer toutes les notes de l'étudiant pour ce trimestre et cette année académique
        $grades = Grade::where('student_id', $student->id)
                       ->where('academic_year_id', $request->academic_year_id)
                       ->where('trimester', $trimester)
                       ->with(['course', 'teacher'])
                       ->get();

        // Grouper les notes par cours
        $courseGrades = [];
        $totalAverage = 0;
        $courseCount = 0;

        foreach ($grades as $grade) {
            $courseId = $grade->course_id;

            if (!isset($courseGrades[$courseId])) {
                $courseGrades[$courseId] = [
                    'course' => $grade->course,
                    'teacher' => $grade->teacher,
                    'grades' => [],
                    'average' => 0,
                    'total_points' => 0,
                    'max_points' => 0
                ];
            }

            $courseGrades[$courseId]['grades'][] = $grade;

            // Pour les examens, on compte différemment (généralement coefficient plus élevé)
            $coefficient = ($grade->grade_type === 'EXAM') ? 2 : 1;
            $courseGrades[$courseId]['total_points'] += $grade->score * $coefficient;
            $courseGrades[$courseId]['max_points'] += $grade->max_score * $coefficient;
        }

        // Calculer la moyenne pour chaque cours
        foreach ($courseGrades as $courseId => &$data) {
            if ($data['max_points'] > 0) {
                $data['average'] = ($data['total_points'] / $data['max_points']) * 20; // Sur 20
                $totalAverage += $data['average'];
                $courseCount++;
            }
        }

        // Moyenne générale
        $generalAverage = $courseCount > 0 ? $totalAverage / $courseCount : 0;

        // Récupérer le classement de l'élève dans sa classe
        $classStudents = Student::where('class_room_id', $student->class_room_id)->get();
        $rankings = [];

        foreach ($classStudents as $classStudent) {
            $studentAverage = $this->calculateStudentAverage($classStudent->id, $request->academic_year_id, $trimester);
            $rankings[$classStudent->id] = $studentAverage;
        }

        // Trier par moyenne décroissante
        arsort($rankings);

        // Trouver le rang de l'étudiant
        $rank = array_search($student->id, array_keys($rankings)) + 1;

        // Générer le PDF
        $data = [
            'student' => $student,
            'academicYear' => $academicYear,
            'trimester' => $trimester,
            'courseGrades' => $courseGrades,
            'generalAverage' => $generalAverage,
            'rank' => $rank,
            'totalStudents' => count($classStudents)
        ];

        $pdf = FacadePdf::loadView('grade.report_card_pdf', $data);

        return $pdf->download('bulletin_' . $student->matricule . '_trimestre_' . $trimester . '.pdf');
    }

    /**
     * Calculer la moyenne d'un étudiant pour un trimestre et une année académique
     */
    private function calculateStudentAverage($studentId, $academicYearId, $trimester)
    {
        // Récupérer toutes les notes de l'étudiant
        $grades = Grade::where('student_id', $studentId)
                       ->where('academic_year_id', $academicYearId)
                       ->where('trimester', $trimester)
                       ->get();

        // Grouper par cours
        $courseAverages = [];

        foreach ($grades as $grade) {
            $courseId = $grade->course_id;

            if (!isset($courseAverages[$courseId])) {
                $courseAverages[$courseId] = [
                    'total_points' => 0,
                    'max_points' => 0
                ];
            }

            // Pour les examens, on compte différemment (généralement coefficient plus élevé)
            $coefficient = ($grade->grade_type === 'EXAM') ? 2 : 1;
            $courseAverages[$courseId]['total_points'] += $grade->score * $coefficient;
            $courseAverages[$courseId]['max_points'] += $grade->max_score * $coefficient;
        }

        // Calculer la moyenne pour chaque cours puis la moyenne générale
        $totalAverage = 0;
        $courseCount = 0;

        foreach ($courseAverages as $courseAverage) {
            if ($courseAverage['max_points'] > 0) {
                $average = ($courseAverage['total_points'] / $courseAverage['max_points']) * 20; // Sur 20
                $totalAverage += $average;
                $courseCount++;
            }
        }

        return $courseCount > 0 ? $totalAverage / $courseCount : 0;
    }

    /**
     * Générer les bulletins PDF pour tous les étudiants d'une classe
     */
    public function generateClassReportCards(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year_id' => 'required|exists:academic_years,id',
            'trimester' => 'required|in:1,2,3',
            'class_id' => 'required|exists:class_rooms,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $students = Student::where('class_room_id', $request->class_id)->get();
        $class = ClassRoom::find($request->class_id);
        $academicYear = AcademicYear::find($request->academic_year_id);

        // Générer un PDF combiné avec tous les bulletins
        $data = [
            'students' => $students,
            'class' => $class,
            'academicYear' => $academicYear,
            'trimester' => $request->trimester,
        ];

        $pdf = FacadePdf::loadView('grade.class_report_cards_pdf', $data);

        return $pdf->download('bulletins_classe_' . $class->name . '_trimestre_' . $request->trimester . '.pdf');
    }

    /**
     * Export grades based on filters
     */
    public function export(Request $request)
    {
        // Récupérer les paramètres de filtre
        $filters = $request->only([
            'academic_year_id',
            'course_id',
            'trimester',
            'teacher_id',
            'search',
            'include_courses',
            'include_students',
            'include_comments'
        ]);

        $format = $request->input('format', 'xlsx');
        $filename = 'notes_' . date('Y-m-d_His') . '.' . $format;

        // Créer un objet d'exportation avec les filtres
        $export = new GradesExport($filters);

        // Exporter en fonction du format demandé
        return Excel::download($export, $filename, $format);
    }

    /**
     * Show grade import form
     */
    public function showImportForm()
    {
        return view('grades .import', [
            'courses' => Course::orderBy('name')->get(),
            'academicYears' => AcademicYear::orderBy('year', 'desc')->get(),
        ]);
    }

    /**
     * Generate an Excel template for grades import
     */
    public function generateTemplate(Request $request)
    {
        $academicYearId = $request->input('academic_year_id');
        $courseId = $request->input('course_id');

        // Si aucun filtre n'est fourni, créer un modèle générique
        $export = new \App\Exports\GradesTemplateExport($academicYearId, $courseId);

        return Excel::download($export, 'modele_import_notes.xlsx');
    }

    /**
     * Import grades from Excel/CSV file
     */
    public function import(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv',
            'academic_year_id' => 'required|exists:academic_years,id',
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Paramètres d'importation
            $importParams = [
                'academic_year_id' => $request->input('academic_year_id'),
                'course_id' => $request->input('course_id'),
                'update_existing' => $request->has('update_existing'),
                'recorded_by' => auth()->user()->teacher->id ?? null,
            ];

            // Importer le fichier Excel
            $import = new GradesImport($importParams);
            Excel::import($import, $request->file('file'));

            // Récupérer les résultats de l'importation
            $importResults = $import->getResults();

            $message = sprintf(
                '%d notes importées, %d mises à jour, %d ignorées, %d erreurs',
                $importResults['imported'] ?? 0,
                $importResults['updated'] ?? 0,
                $importResults['skipped'] ?? 0,
                $importResults['errors'] ?? 0
            );

            return redirect()->route('grades.index')
                ->with('success', 'Importation des notes réussie. ' . $message);

        } catch (\Exception $e) {
            Log::error('Erreur d\'importation de notes: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage())
                ->withInput();
        }
    }
}
