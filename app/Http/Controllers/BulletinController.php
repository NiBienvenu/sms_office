<?php

namespace App\Http\Controllers;

use App\Models\Bulletin;
use App\Models\Student;
use App\Models\ClassRoom;
use App\Models\AcademicYear;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;

class BulletinController extends Controller
{
    /**
     * Display a listing of the bulletins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Bulletin::with(['student', 'classRoom', 'academicYear'])
            ->when($request->search, function ($q) use ($request) {
                return $q->whereHas('student', function ($query) use ($request) {
                    $query->where('first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%')
                        ->orWhere('student_id', 'like', '%' . $request->search . '%');
                });
            })
            ->when($request->academic_year_id, function ($q) use ($request) {
                return $q->where('academic_year_id', $request->academic_year_id);
            })
            ->when($request->class_room_id, function ($q) use ($request) {
                return $q->where('class_room_id', $request->class_room_id);
            })
            ->when($request->trimester, function ($q) use ($request) {
                return $q->where('trimester', $request->trimester);
            })
            ->when($request->status, function ($q) use ($request) {
                return $q->where('status', $request->status);
            })
            ->latest('generated_at');

        $bulletins = $query->paginate(15)->withQueryString();

        // Statistiques
        $totalBulletins = Bulletin::count();
        $pendingBulletins = Bulletin::where('status', 'pending')->count();
        $publishedBulletins = Bulletin::where('status', 'published')->count();
        $totalDownloads = Bulletin::whereNotNull('pdf_path')->count();

        // Nombre d'élèves par classe pour le calcul du rang
        $totalStudentsInClass = ClassRoom::withCount('students')
            ->pluck('students_count', 'id')
            ->toArray();

        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $classRooms = ClassRoom::orderBy('name')->get();
        $teachers = Teacher::orderBy('last_name')->get();

        return view('bulletin.index', compact(
            'bulletins',
            'academicYears',
            'classRooms',
            'teachers',
            'totalBulletins',
            'pendingBulletins',
            'publishedBulletins',
            'totalDownloads',
            'totalStudentsInClass'
        ));
    }

    /**
     * Show the form for creating a new bulletin.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students = Student::orderBy('last_name')->get();
        $classRooms = ClassRoom::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('bulletin.create', compact('students', 'classRooms', 'academicYears'));
    }

    /**
     * Store a newly created bulletin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_room_id' => 'required|exists:class_rooms,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'trimester' => 'required|integer|min:1|max:3',
        ]);

        // Vérification si un bulletin existe déjà pour cet élève, cette classe, cette année et ce trimestre
        $existingBulletin = Bulletin::where('student_id', $request->student_id)
            ->where('class_room_id', $request->class_room_id)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('trimester', $request->trimester)
            ->first();

        if ($existingBulletin) {
            return redirect()->back()->with('error', 'Un bulletin existe déjà pour cet élève dans ce trimestre.');
        }

        // Création du bulletin
        $bulletin = Bulletin::create([
            'student_id' => $request->student_id,
            'class_room_id' => $request->class_room_id,
            'academic_year_id' => $request->academic_year_id,
            'trimester' => $request->trimester,
            'generated_at' => now(),
            'status' => 'draft',
        ]);

        // Calcul de la moyenne et du rang
        $this->calculateBulletinStats($bulletin);

        return redirect()->route('bulletins.edit', $bulletin)
            ->with('success', 'Bulletin créé avec succès. Vous pouvez maintenant le compléter.');
    }

    /**
     * Display the specified bulletin.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function show(Bulletin $bulletin)
    {
        $bulletin->load(['student', 'classRoom', 'academicYear']);

        // Récupération des notes de l'élève pour ce trimestre
        $grades = Grade::where('student_id', $bulletin->student_id)
            ->where('academic_year_id', $bulletin->academic_year_id)
            ->where('trimester', $bulletin->trimester)
            ->with(['course', 'teacher'])
            ->get();

        // Regroupement des notes par matière
        $gradesByCourse = $grades->groupBy('course_id');

        // Calcul des moyennes par matière
        $courseAverages = [];
        foreach ($gradesByCourse as $courseId => $courseGrades) {
            $totalWeighted = 0;
            $totalWeight = 0;

            foreach ($courseGrades as $grade) {
                $weight = 1; // Poids par défaut
                switch ($grade->grade_type) {
                    case 'Examen':
                        $weight = 3;
                        break;
                    case 'Devoir':
                        $weight = 2;
                        break;
                    case 'Quiz':
                        $weight = 1;
                        break;
                }

                $totalWeighted += ($grade->percentage * $weight);
                $totalWeight += $weight;
            }

            $average = $totalWeight > 0 ? $totalWeighted / $totalWeight : 0;
            $course = Course::find($courseId);
            $courseAverages[$courseId] = [
                'course' => $course,
                'average' => $average,
                'coefficient' => $course->coefficient ?? 1,
                'grades' => $courseGrades,
                'teacher' => $courseGrades->first()->teacher
            ];
        }

        // Statistiques de la classe
        $classAverage = DB::table('bulletins')
            ->where('class_room_id', $bulletin->class_room_id)
            ->where('academic_year_id', $bulletin->academic_year_id)
            ->where('trimester', $bulletin->trimester)
            ->avg('average');

        $totalStudents = DB::table('bulletins')
            ->where('class_room_id', $bulletin->class_room_id)
            ->where('academic_year_id', $bulletin->academic_year_id)
            ->where('trimester', $bulletin->trimester)
            ->count();

        // Status en français pour l'affichage
        $status = 'Non défini';
        switch ($bulletin->status) {
            case 'draft':
                $status = 'Brouillon';
                break;
            case 'pending':
                $status = 'En attente';
                break;
            case 'published':
                $status = 'Publié';
                break;
        }

        return view('bulletin.show', compact(
            'bulletin',
            'courseAverages',
            'classAverage',
            'totalStudents',
            'status',
            'grades'
        ));
    }

    /**
     * Show the form for editing the specified bulletin.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function edit(Bulletin $bulletin)
    {
        $bulletin->load(['student', 'classRoom', 'academicYear']);

        // Récupération des notes de l'élève pour ce trimestre
        $grades = Grade::where('student_id', $bulletin->student_id)
            ->where('academic_year_id', $bulletin->academic_year_id)
            ->where('trimester', $bulletin->trimester)
            ->with(['course', 'teacher'])
            ->get();

        // Récupération de toutes les matières pour cette classe
        $courses = Course::all();

        return view('bulletin.edit', compact('bulletin', 'grades', 'courses'));
    }

    /**
     * Update the specified bulletin in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bulletin $bulletin)
    {
        $request->validate([
            'teacher_comments' => 'nullable|string',
            'principal_comments' => 'nullable|string',
            'status' => 'nullable|string|in:draft,pending,published',
        ]);

        $bulletin->update([
            'teacher_comments' => $request->teacher_comments,
            'principal_comments' => $request->principal_comments,
            'status' => $request->status ?? $bulletin->status,
        ]);

        // Mise à jour des statistiques
        $this->calculateBulletinStats($bulletin);

        return redirect()->route('bulletins.show', $bulletin)
            ->with('success', 'Bulletin mis à jour avec succès.');
    }

    /**
     * Remove the specified bulletin from storage.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bulletin $bulletin)
    {
        // Suppression du fichier PDF s'il existe
        if ($bulletin->pdf_path) {
            Storage::delete($bulletin->pdf_path);
        }

        $bulletin->delete();

        return redirect()->route('bulletins.index')
            ->with('success', 'Bulletin supprimé avec succès.');
    }

    /**
     * Show form to generate bulletins.
     *
     * @return \Illuminate\Http\Response
     */
    public function generateForm()
    {
        $classRooms = ClassRoom::orderBy('name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $students = Student::all();

        return view('bulletin.generate', compact('classRooms', 'academicYears','students'));
    }

    /**
     * Generate bulletins for all students in a class.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generateByClass(Request $request)
    {
        $request->validate([
            'class_room_id' => 'required|exists:class_rooms,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'trimester' => 'required|integer|min:1|max:3',
        ]);

        $classRoom = ClassRoom::findOrFail($request->class_room_id);
        $students = $classRoom->students;
        $createdCount = 0;

        foreach ($students as $student) {
            // Vérification si un bulletin existe déjà
            $existingBulletin = Bulletin::where('student_id', $student->id)
                ->where('class_room_id', $request->class_room_id)
                ->where('academic_year_id', $request->academic_year_id)
                ->where('trimester', $request->trimester)
                ->first();

            if (!$existingBulletin) {
                // Création du nouveau bulletin
                $bulletin = Bulletin::create([
                    'student_id' => $student->id,
                    'class_room_id' => $request->class_room_id,
                    'academic_year_id' => $request->academic_year_id,
                    'trimester' => $request->trimester,
                    'generated_at' => now(),
                    'status' => $request->auto_publish ? 'published' : 'draft',
                ]);

                // Calcul des statistiques
                $this->calculateBulletinStats($bulletin);

                $createdCount++;
            }
        }

        return redirect()->route('bulletins.index', [
            'academic_year_id' => $request->academic_year_id,
            'class_room_id' => $request->class_room_id,
            'trimester' => $request->trimester
        ])->with('success', $createdCount . ' bulletin(s) généré(s) avec succès.');
    }

    /**
     * Publish selected bulletins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function publishSelected(Request $request)
    {
        $request->validate([
            'bulletin_ids' => 'required|string',
        ]);

        $ids = explode(',', $request->bulletin_ids);
        $updatedCount = 0;

        foreach ($ids as $id) {
            $bulletin = Bulletin::find($id);
            if ($bulletin) {
                $bulletin->update([
                    'status' => 'published',
                ]);
                $updatedCount++;
            }
        }

        return redirect()->back()
            ->with('success', $updatedCount . ' bulletin(s) publié(s) avec succès.');
    }

    /**
     * Generate PDF for a bulletin.
     *
     * @param  \App\Models\Bulletin  $bulletin
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Bulletin $bulletin)
    {
        // Récupérer les notes associées à l'étudiant
        $grades = $this->getStudentGrades($bulletin);

        // Calculer les moyennes par matière
        $courseAverages = $this->calculateCourseAverages($grades, $bulletin);

        // Récupérer les informations de la classe
        $classStats = $this->getClassStatistics($bulletin);

        $pdf = FacadePdf::loadView('bulletin.pdf', [
            'bulletin' => $bulletin,
            'grades' => $grades,
            'courseAverages' => $courseAverages,
            'classStats' => $classStats
        ]);

        // Sauvegarder le PDF dans le storage si pas encore fait
        if (!$bulletin->pdf_path) {
            $filename = 'bulletin_' . $bulletin->student_id . '_' . $bulletin->trimester . '_' . time() . '.pdf';
            $path = 'bulletins/' . $bulletin->academic_year_id . '/' . $bulletin->class_room_id;
            Storage::makeDirectory('public/' . $path);

            $fullPath = $path . '/' . $filename;
            Storage::put('public/' . $fullPath, $pdf->output());

            $bulletin->pdf_path = $fullPath;
            $bulletin->save();
        }

        return $pdf->stream('bulletin_' . $bulletin->student->last_name . '.pdf');
    }

    /**
     * Imprimer un bulletin spécifique
     */
    public function printBulletin(Bulletin $bulletin)
    {
        // Récupérer les notes associées à l'étudiant
        $grades = $this->getStudentGrades($bulletin);

        // Calculer les moyennes par matière
        $courseAverages = $this->calculateCourseAverages($grades, $bulletin);

        // Récupérer les informations de la classe
        $classStats = $this->getClassStatistics($bulletin);

        return view('bulletin.print', [
            'bulletin' => $bulletin,
            'grades' => $grades,
            'courseAverages' => $courseAverages,
            'classStats' => $classStats
        ]);
    }

    /**
     * Imprimer tous les bulletins filtrés
     */
    public function printAll(Request $request)
    {
        $query = Bulletin::with(['student', 'classRoom', 'academicYear']);

        // Appliquer les filtres
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('class_room_id')) {
            $query->where('class_room_id', $request->class_room_id);
        }

        if ($request->filled('trimester')) {
            $query->where('trimester', $request->trimester);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $bulletins = $query->get();

        return view('bulletin.print_all', [
            'bulletins' => $bulletins
        ]);
    }

    /**
     * Exporter plusieurs bulletins sélectionnés
     */
    public function exportSelected(Request $request)
    {
        $request->validate([
            'bulletin_ids' => 'required',
            'include_header' => 'nullable|boolean',
            'include_footer' => 'nullable|boolean',
        ]);

        $bulletinIds = explode(',', $request->bulletin_ids);
        $bulletins = Bulletin::whereIn('id', $bulletinIds)->get();

        if ($bulletins->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun bulletin sélectionné pour l\'export.');
        }

        // Créer un PDF combiné
        $pdf = FacadePdf::loadView('bulletins.export_multiple', [
            'bulletins' => $bulletins,
            'includeHeader' => $request->filled('include_header'),
            'includeFooter' => $request->filled('include_footer')
        ]);

        return $pdf->stream('bulletins_' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    /**
     * Ajouter un commentaire d'enseignant
     */
    public function addTeacherComment(Request $request, Bulletin $bulletin)
    {
        $request->validate([
            'teacher_comments' => 'required|string|max:500'
        ]);

        $bulletin->teacher_comments = $request->teacher_comments;
        $bulletin->save();

        // Supprimer le PDF existant pour qu'il soit régénéré avec les nouveaux commentaires
        if ($bulletin->pdf_path) {
            Storage::delete('public/' . $bulletin->pdf_path);
            $bulletin->pdf_path = null;
            $bulletin->save();
        }

        return redirect()->back()->with('success', 'Commentaire ajouté avec succès.');
    }

    /**
     * Ajouter un commentaire du directeur
     */
    public function addPrincipalComment(Request $request, Bulletin $bulletin)
    {
        $request->validate([
            'principal_comments' => 'required|string|max:500'
        ]);

        $bulletin->principal_comments = $request->principal_comments;
        $bulletin->save();

        // Supprimer le PDF existant pour qu'il soit régénéré avec les nouveaux commentaires
        if ($bulletin->pdf_path) {
            Storage::delete('public/' . $bulletin->pdf_path);
            $bulletin->pdf_path = null;
            $bulletin->save();
        }

        return redirect()->back()->with('success', 'Commentaire du directeur ajouté avec succès.');
    }

    /**
     * Publier un bulletin (changer son statut en "published")
     */
    public function publish(Request $request, Bulletin $bulletin)
    {
        // Vérifier si le bulletin a tous les éléments nécessaires
        if (!$bulletin->average) {
            return redirect()->back()->with('error', 'Impossible de publier : la moyenne n\'a pas été calculée.');
        }

        $bulletin->status = 'published';
        $bulletin->save();

        // Générer le PDF si ce n'est pas déjà fait
        if (!$bulletin->pdf_path) {
            $this->generatePdf($bulletin);
        }

        return redirect()->back()->with('success', 'Bulletin publié avec succès.');
    }

    /**
     * Recalculer les moyennes d'un bulletin
     */
    public function recalculate(Bulletin $bulletin)
    {
        // Récupérer toutes les notes de l'étudiant pour le trimestre
        $grades = $this->getStudentGrades($bulletin);

        if ($grades->isEmpty()) {
            return redirect()->back()->with('error', 'Aucune note trouvée pour ce bulletin.');
        }

        // Calculer la moyenne
        $totalPoints = 0;
        $totalCoefficients = 0;

        // Grouper par matière pour calculer d'abord les moyennes par matière
        $gradesByCourse = $grades->groupBy('course_id');

        foreach ($gradesByCourse as $courseId => $courseGrades) {
            $course = Course::find($courseId);
            $coefficient = $course->coefficient ?? 1;

            // Calculer la moyenne des notes pour cette matière
            $courseAverage = $courseGrades->avg('percentage');

            // Ajouter à la moyenne générale pondérée
            $totalPoints += $courseAverage * $coefficient;
            $totalCoefficients += $coefficient;
        }

        // Calculer la moyenne générale
        $average = $totalCoefficients > 0 ? $totalPoints / $totalCoefficients : 0;
        $average = number_format($average, 2);

        // Mettre à jour le bulletin
        $bulletin->average = $average;

        // Calculer le rang
        $this->calculateRanks($bulletin->class_room_id, $bulletin->academic_year_id, $bulletin->trimester);

        // Si le bulletin était publié, le remettre en état de brouillon
        if ($bulletin->status == 'published') {
            $bulletin->status = 'pending';
        }

        // Supprimer le PDF existant
        if ($bulletin->pdf_path) {
            Storage::delete('public/' . $bulletin->pdf_path);
            $bulletin->pdf_path = null;
        }

        $bulletin->save();

        return redirect()->back()->with('success', 'Moyennes recalculées avec succès.');
    }

    /**
     * Calculer les rangs pour tous les bulletins d'une classe
     */
    private function calculateRanks($classRoomId, $academicYearId, $trimester)
    {
        // Récupérer tous les bulletins de la classe pour le trimestre spécifié
        $bulletins = Bulletin::where('class_room_id', $classRoomId)
            ->where('academic_year_id', $academicYearId)
            ->where('trimester', $trimester)
            ->whereNotNull('average')
            ->orderByDesc('average')
            ->get();

        $rank = 1;
        foreach ($bulletins as $bulletin) {
            $bulletin->rank = $rank++;
            $bulletin->save();
        }

        return true;
    }

    /**
     * Récupérer les notes d'un étudiant pour un bulletin
     */
    private function getStudentGrades(Bulletin $bulletin)
    {
        return Grade::where('student_id', $bulletin->student_id)
            ->where('academic_year_id', $bulletin->academic_year_id)
            ->where('trimester', $bulletin->trimester)
            ->with(['course', 'teacher'])
            ->get();
    }

    /**
     * Calculer les moyennes par matière
     */
    private function calculateCourseAverages($grades, Bulletin $bulletin)
    {
        $courseAverages = [];

        // Grouper les notes par matière
        $gradesByCourse = $grades->groupBy('course_id');

        foreach ($gradesByCourse as $courseId => $courseGrades) {
            $course = Course::find($courseId);

            // Moyenne des notes pour cette matière
            $average = $courseGrades->avg('percentage');

            // Récupérer les meilleures et moins bonnes notes de la classe pour cette matière
            $classStats = DB::table('grades')
                ->select(DB::raw('MAX(percentage) as max_score, MIN(percentage) as min_score, AVG(percentage) as avg_score'))
                ->where('course_id', $courseId)
                ->where('academic_year_id', $bulletin->academic_year_id)
                ->where('trimester', $bulletin->trimester)
                ->whereIn('student_id', function($query) use ($bulletin) {
                    $query->select('id')
                        ->from('students')
                        ->where('class_room_id', $bulletin->class_room_id);
                })
                ->first();

            $courseAverages[$courseId] = [
                'course' => $course,
                'average' => number_format($average, 2),
                'class_max' => $classStats ? number_format($classStats->max_score, 2) : 0,
                'class_min' => $classStats ? number_format($classStats->min_score, 2) : 0,
                'class_avg' => $classStats ? number_format($classStats->avg_score, 2) : 0,
                'grades' => $courseGrades
            ];
        }

        return $courseAverages;
    }
    public static function calculateBulletinStats(Bulletin $bulletin)
    {
        // Get all grades for this student, course, trimester and academic year
        $grades = Grade::where('student_id', $bulletin->student_id)
            ->where('academic_year_id', $bulletin->academic_year_id)
            ->where('trimester', $bulletin->trimester)
            ->get();

        if ($grades->isEmpty()) {
            return [
                'average' => null,
                'totalCoefficient' => 0,
                'courseAverages' => collect(),
                'hasPassed' => false,
                'gradeCount' => 0,
            ];
        }

        // Group grades by course
        $courseGrades = $grades->groupBy('course_id');
        $totalPoints = 0;
        $totalCoefficient = 0;
        $courseAverages = collect();

        // Calculate average per course
        foreach ($courseGrades as $courseId => $gradeGroup) {
            $course = Course::find($courseId);
            $coefficient = $course->coefficient ?? 1;

            // Calculate average for this course
            $courseTotal = 0;
            foreach ($gradeGroup as $grade) {
                $courseTotal += ($grade->score / $grade->max_score) * 20; // Convert to score out of 20
            }

            $courseAverage = $courseTotal / count($gradeGroup);
            $weightedAverage = $courseAverage * $coefficient;

            $totalPoints += $weightedAverage;
            $totalCoefficient += $coefficient;

            $courseAverages->push([
                'course_id' => $courseId,
                'course_name' => $course->name,
                'coefficient' => $coefficient,
                'average' => $courseAverage,
                'grades' => $gradeGroup,
            ]);
        }

        // Calculate overall average
        $average = $totalCoefficient > 0 ? $totalPoints / $totalCoefficient : 0;

        return [
            'average' => round($average, 2),
            'totalCoefficient' => $totalCoefficient,
            'courseAverages' => $courseAverages,
            'hasPassed' => $average >= 10,
            'gradeCount' => $grades->count(),
        ];
    }

    /**
     * Get class statistics for a given class, trimester and academic year
     *
     * @param int $classRoomId
     * @param int $academicYearId
     * @param int $trimester
     * @return array
     */
    private function getClassStatistics(Bulletin $bulletin)
    {
        // Get all bulletins for the same class, trimester, and academic year
        $classBulletins = Bulletin::where('class_room_id', $bulletin->class_room_id)
            ->where('trimester', $bulletin->trimester)
            ->where('academic_year_id', $bulletin->academic_year_id)
            ->where('status', '!=', 'draft')
            ->get();

        // Calculate class average
        $classAverage = $classBulletins->avg('average') ?? 0;
        $classAverage = number_format($classAverage, 2);

        // Get total number of students
        $totalStudents = $classBulletins->count();

        // Get highest and lowest averages
        $highestAverage = $classBulletins->max('average') ?? 0;
        $lowestAverage = $classBulletins->min('average') ?? 0;

        // Get pass rate (average >= 10)
        $passCount = $classBulletins->filter(function ($item) {
            return $item->average >= 10;
        })->count();

        $passRate = $totalStudents > 0 ? ($passCount / $totalStudents) * 100 : 0;
        $passRate = number_format($passRate, 1);

        // Get the top 3 students
        $topStudents = $classBulletins->sortByDesc('average')->take(3);

        return [
            'classAverage' => $classAverage,
            'totalStudents' => $totalStudents,
            'highestAverage' => $highestAverage,
            'lowestAverage' => $lowestAverage,
            'passRate' => $passRate,
            'topStudents' => $topStudents,
        ];
    }
}
