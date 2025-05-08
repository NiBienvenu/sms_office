<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['course', 'teacher', 'academicYear']);

        if ($request->filled('course')) {
            $query->where('course_id', $request->course);
        }
        if ($request->filled('teacher')) {
            $query->where('teacher_id', $request->teacher);
        }
        if ($request->filled('day')) {
            $query->where('day_of_week', $request->day);
        }
        if ($request->filled('academic_year')) {
            $query->where('academic_year_id', $request->academic_year);
        }

        // Tri par jour de la semaine et heure de début
        $query->orderByRaw("FIELD(day_of_week, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')")
              ->orderBy('start_time');

        $schedules = $query->paginate(10);
        $courses = Course::orderBy('code')->get();
        $teachers = Teacher::orderBy('first_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('schedule.index', compact('schedules', 'courses', 'teachers', 'academicYears'));
    }

    public function create()
    {
        $courses = Course::orderBy('code')->get();
        $teachers = Teacher::orderBy('first_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('schedule.create', compact('courses', 'teachers', 'academicYears'));
    }

    public function store(Request $request)
    {
        // Validation pour un horaire unique
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'required|string|max:255',
        ]);

        // Vérifier si c'est un enregistrement par semaine
        if ($request->has('weekly_schedule')) {
            $daysOfWeek = $request->input('days', []);
            $createdCount = 0;

            foreach ($daysOfWeek as $day) {
                // Vérifier les conflits d'horaire pour la salle et l'enseignant
                $this->checkScheduleConflicts(
                    $day,
                    $request->start_time,
                    $request->end_time,
                    $request->room,
                    $request->teacher_id,
                    $request->academic_year_id
                );

                Schedule::create([
                    'course_id' => $request->course_id,
                    'teacher_id' => $request->teacher_id,
                    'academic_year_id' => $request->academic_year_id,
                    'day_of_week' => $day,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'room' => $request->room,
                ]);

                $createdCount++;
            }

            return redirect()->route('schedules.index')
                ->with('success', "$createdCount horaires créés avec succès.");
        } else {
            // Vérifier les conflits d'horaire pour la salle et l'enseignant
            $this->checkScheduleConflicts(
                $request->day_of_week,
                $request->start_time,
                $request->end_time,
                $request->room,
                $request->teacher_id,
                $request->academic_year_id
            );

            Schedule::create($validated);

            return redirect()->route('schedules.index')
                ->with('success', 'Horaire créé avec succès.');
        }
    }

    private function checkScheduleConflicts($day, $startTime, $endTime, $room, $teacherId, $academicYearId)
    {
        // Vérifier les conflits de salle
        $roomConflict = Schedule::where('day_of_week', $day)
            ->where('academic_year_id', $academicYearId)
            ->where('room', $room)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })->first();

        if ($roomConflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['conflict' => "La salle $room est déjà occupée à cette heure."]);
        }

        // Vérifier les conflits d'enseignant
        $teacherConflict = Schedule::where('day_of_week', $day)
            ->where('academic_year_id', $academicYearId)
            ->where('teacher_id', $teacherId)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })->first();

        if ($teacherConflict) {
            $teacher = Teacher::find($teacherId);
            return redirect()->back()
                ->withInput()
                ->withErrors(['conflict' => "L'enseignant {$teacher->fullname} a déjà un cours à cette heure."]);
        }
    }

    public function show(Schedule $schedule)
    {
        return view('schedule.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $courses = Course::orderBy('code')->get();
        $teachers = Teacher::orderBy('first_name')->get();
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();

        return view('schedule.edit', compact('schedule', 'courses', 'teachers', 'academicYears'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'required|string|max:255',
        ]);

        // Vérifier les conflits sauf pour cet horaire lui-même
        $roomConflict = Schedule::where('id', '!=', $schedule->id)
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('room', $request->room)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })->first();

        if ($roomConflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['conflict' => "La salle {$request->room} est déjà occupée à cette heure."]);
        }

        $teacherConflict = Schedule::where('id', '!=', $schedule->id)
            ->where('day_of_week', $request->day_of_week)
            ->where('academic_year_id', $request->academic_year_id)
            ->where('teacher_id', $request->teacher_id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function($q) use ($request) {
                        $q->where('start_time', '<=', $request->start_time)
                          ->where('end_time', '>=', $request->end_time);
                    });
            })->first();

        if ($teacherConflict) {
            $teacher = Teacher::find($request->teacher_id);
            return redirect()->back()
                ->withInput()
                ->withErrors(['conflict' => "L'enseignant {$teacher->fullname} a déjà un cours à cette heure."]);
        }

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Horaire mis à jour avec succès.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Horaire supprimé avec succès.');
    }

    // Générer un PDF des horaires par jour
    public function generateDailyPdf(Request $request)
    {
        $query = Schedule::with(['course', 'teacher', 'academicYear']);

        if ($request->filled('day')) {
            $query->where('day_of_week', $request->day);
        }
        if ($request->filled('academic_year')) {
            $query->where('academic_year_id', $request->academic_year);
        }

        $query->orderBy('start_time');

        $schedules = $query->get();
        $academicYear = $request->filled('academic_year')
            ? AcademicYear::find($request->academic_year)->year
            : 'Tous';
        $day = $request->filled('day') ? $request->day : 'Tous les jours';

        $pdf = FacadePdf::loadView('schedule.pdf.daily', compact('schedules', 'day', 'academicYear'));

        return $pdf->download("horaires_{$day}_{$academicYear}.pdf");
    }

    // Générer un PDF des horaires par semaine
    public function generateWeeklyPdf(Request $request)
    {
        // dd($request->filled('academic_year'));
        $academicYearId = $request->filled('academic_year') ? $request->academic_year : null;

        // Récupérer tous les jours de la semaine
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $weeklySchedules = [];

        // Obtenir les heures min et max pour toutes les sessions
        $timeRange = Schedule::selectRaw('MIN(start_time) as min_time, MAX(end_time) as max_time')
            ->when($academicYearId, function($query) use ($academicYearId) {
                return $query->where('academic_year_id', $academicYearId);
            })
            ->first();

        // Créer des plages horaires de 30 minutes entre min et max
        $startTime = Carbon::parse($timeRange->min_time ?? '08:00');
        $endTime = Carbon::parse($timeRange->max_time ?? '18:00');

        $timeSlots = [];
        while ($startTime <= $endTime) {
            $timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }

        // Récupérer tous les cours organisés par jour et créneau horaire
        foreach ($daysOfWeek as $day) {
            $daySchedules = Schedule::with(['course', 'teacher'])
                ->where('day_of_week', $day)
                ->when($academicYearId, function($query) use ($academicYearId) {
                    return $query->where('academic_year_id', $academicYearId);
                })
                ->orderBy('start_time')
                ->get();

            $weeklySchedules[$day] = $daySchedules;
        }

        $academicYear = $academicYearId
            ? AcademicYear::find($academicYearId)->year
            : 'Tous';

        $pdf = FacadePdf::loadView('schedule.pdf.weekly', [
            'weeklySchedules' => $weeklySchedules,
            'timeSlots' => $timeSlots,
            'academicYear' => $academicYear
        ]);

        return $pdf->download("horaires_hebdomadaires_{$academicYear}.pdf");
    }

    // Vue de l'emploi du temps hebdomadaire
    public function weeklyView(Request $request)
    {
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $academicYearId = $request->filled('academic_year') ? $request->academic_year :
            ($academicYears->first() ? $academicYears->first()->id : null);

        // Récupérer tous les jours de la semaine
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        $weeklySchedules = [];

        // Pour chaque jour, récupérer les emplois du temps
        foreach ($daysOfWeek as $day) {
            $daySchedules = Schedule::with(['course', 'teacher'])
                ->where('day_of_week', $day)
                ->where('academic_year_id', $academicYearId)
                ->orderBy('start_time')
                ->get();

            $weeklySchedules[$day] = $daySchedules;
        }

        return view('schedule.weekly', compact('weeklySchedules', 'academicYears', 'academicYearId', 'daysOfWeek'));
    }
}
