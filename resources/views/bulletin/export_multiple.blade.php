<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Export des bulletins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            text-align: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .school-logo {
            max-width: 80px;
            margin-bottom: 10px;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .school-info {
            font-size: 11px;
            margin-bottom: 5px;
        }
        .bulletin-header {
            background-color: #f3f3f3;
            padding: 10px;
            margin-bottom: 15px;
        }
        .student-info {
            width: 100%;
            margin-bottom: 15px;
        }
        .student-info td {
            padding: 5px;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .grades-table th, .grades-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        .grades-table th {
            background-color: #f3f3f3;
            font-weight: bold;
        }
        .course-row {
            background-color: #f9f9f9;
        }
        .average-row {
            font-weight: bold;
        }
        .comments {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
        }
        .comments h4 {
            margin-top: 0;
            margin-bottom: 5px;
            color: #333;
        }
        .signature {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .signature-box {
            width: 30%;
            border-top: 1px dotted #000;
            padding-top: 5px;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 10px;
            border-top: 1px solid #ddd;
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }
        .pass-fail {
            font-size: 14px;
            font-weight: bold;
            padding: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        .pass {
            color: green;
            border: 1px solid green;
        }
        .fail {
            color: red;
            border: 1px solid red;
        }
        .stats {
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .stats-table {
            width: 100%;
            border-collapse: collapse;
        }
        .stats-table td {
            padding: 3px;
        }
    </style>
</head>
<body>
    @foreach($bulletins as $index => $bulletin)
        @php
            // Récupération des notes associées à l'étudiant
            $grades = \App\Models\Grade::where('student_id', $bulletin->student_id)
                ->where('academic_year_id', $bulletin->academic_year_id)
                ->where('trimester', $bulletin->trimester)
                ->with(['course', 'teacher'])
                ->get();

            // Regroupement des notes par matière
            $gradesByCourse = $grades->groupBy('course_id');

            // Calcul des moyennes par matière
            $courseAverages = [];
            $totalPoints = 0;
            $totalCoefficients = 0;

            foreach ($gradesByCourse as $courseId => $courseGrades) {
                $course = \App\Models\Course::find($courseId);
                $coefficient = $course->coefficient ?? 1;

                // Calcul de la moyenne pour cette matière
                $average = $courseGrades->avg('percentage');

                $courseAverages[$courseId] = [
                    'course' => $course,
                    'average' => number_format($average, 2),
                    'coefficient' => $coefficient,
                    'weighted_average' => $average * $coefficient,
                    'grades' => $courseGrades
                ];

                $totalPoints += $average * $coefficient;
                $totalCoefficients += $coefficient;
            }

            // Statistiques de la classe
            $classAverage = \Illuminate\Support\Facades\DB::table('bulletins')
                ->where('class_room_id', $bulletin->class_room_id)
                ->where('academic_year_id', $bulletin->academic_year_id)
                ->where('trimester', $bulletin->trimester)
                ->avg('average');

            $totalStudents = \Illuminate\Support\Facades\DB::table('bulletins')
                ->where('class_room_id', $bulletin->class_room_id)
                ->where('academic_year_id', $bulletin->academic_year_id)
                ->where('trimester', $bulletin->trimester)
                ->count();
        @endphp

        @if($includeHeader)
        <div class="header">
            <img class="school-logo" src="{{ public_path('images/school-logo.png') }}" alt="Logo de l'école">
            <div class="school-name">ÉTABLISSEMENT SCOLAIRE</div>
            <div class="school-info">Adresse: 123 Rue de l'École, Ville</div>
            <div class="school-info">Téléphone: +123 456 7890 | Email: contact@ecole.com</div>
        </div>
        @endif

        <div class="bulletin-header">
            <h2 style="text-align: center; margin: 0;">BULLETIN DE NOTES - TRIMESTRE {{ $bulletin->trimester }}</h2>
            <h3 style="text-align: center; margin: 5px 0;">Année Académique: {{ $bulletin->academicYear->year }}</h3>
        </div>

        <table class="student-info">
            <tr>
                <td><strong>Nom & Prénom:</strong> {{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</td>
                <td><strong>ID Étudiant:</strong> {{ $bulletin->student->student_id }}</td>
            </tr>
            <tr>
                <td><strong>Classe:</strong> {{ $bulletin->classRoom->name }}</td>
                <td><strong>Date d'émission:</strong> {{ $bulletin->generated_at ? $bulletin->generated_at->format('d/m/Y') : date('d/m/Y') }}</td>
            </tr>
        </table>

        <table class="grades-table">
            <thead>
                <tr>
                    <th rowspan="2">Matière</th>
                    <th rowspan="2">Coef.</th>
                    <th colspan="3">Notes</th>
                    <th rowspan="2">Moyenne</th>
                    <th rowspan="2">Moy. Pondérée</th>
                    <th colspan="2">Classe</th>
                </tr>
                <tr>
                    <th>Dev.</th>
                    <th>Exam.</th>
                    <th>Oral</th>
                    <th>Min</th>
                    <th>Max</th>
                </tr>
            </thead>
            <tbody>
                @php $totalWeightedAverage = 0; $totalCoeff = 0; @endphp

                @foreach($courseAverages as $courseData)
                    @php
                        $totalWeightedAverage += $courseData['weighted_average'];
                        $totalCoeff += $courseData['coefficient'];

                        // Récupérer les différents types de notes
                        $devoir = $courseData['grades']->where('grade_type', 'Devoir')->avg('percentage') ?? '-';
                        $examen = $courseData['grades']->where('grade_type', 'Examen')->avg('percentage') ?? '-';
                        $oral = $courseData['grades']->where('grade_type', 'Quiz')->avg('percentage') ?? '-';

                        // Pour les min/max de la classe
                        $classMin = \Illuminate\Support\Facades\DB::table('grades')
                            ->where('course_id', $courseData['course']->id)
                            ->where('academic_year_id', $bulletin->academic_year_id)
                            ->where('trimester', $bulletin->trimester)
                            ->min('percentage') ?? '-';

                        $classMax = \Illuminate\Support\Facades\DB::table('grades')
                            ->where('course_id', $courseData['course']->id)
                            ->where('academic_year_id', $bulletin->academic_year_id)
                            ->where('trimester', $bulletin->trimester)
                            ->max('percentage') ?? '-';
                    @endphp

                    <tr class="course-row">
                        <td>{{ $courseData['course']->name }}</td>
                        <td>{{ $courseData['coefficient'] }}</td>
                        <td>{{ is_numeric($devoir) ? number_format($devoir, 1) : $devoir }}</td>
                        <td>{{ is_numeric($examen) ? number_format($examen, 1) : $examen }}</td>
                        <td>{{ is_numeric($oral) ? number_format($oral, 1) : $oral }}</td>
                        <td><strong>{{ $courseData['average'] }}</strong></td>
                        <td>{{ number_format($courseData['weighted_average'], 2) }}</td>
                        <td>{{ is_numeric($classMin) ? number_format($classMin, 1) : $classMin }}</td>
                        <td>{{ is_numeric($classMax) ? number_format($classMax, 1) : $classMax }}</td>
                    </tr>
                @endforeach

                <tr class="average-row">
                    <td colspan="2"><strong>MOYENNE GÉNÉRALE</strong></td>
                    <td colspan="3"></td>
                    <td><strong>{{ number_format($totalCoeff > 0 ? $totalWeightedAverage / $totalCoeff : 0, 2) }}/20</strong></td>
                    <td><strong>{{ number_format($totalWeightedAverage, 2) }}</strong></td>
                    <td colspan="2"></td>
                </tr>
            </tbody>
        </table>

        <div class="stats">
            <table class="stats-table">
                <tr>
                    <td><strong>Rang:</strong> {{ $bulletin->rank ?? '-' }}/{{ $totalStudents }}</td>
                    <td><strong>Moyenne de la classe:</strong> {{ number_format($classAverage, 2) ?? '-' }}/20</td>
                </tr>
                <tr>
                    <td><strong>Absences:</strong> {{ $bulletin->student->absences_count ?? 0 }} jours</td>
                    <td>
                        <span class="pass-fail {{ ($bulletin->average >= 10) ? 'pass' : 'fail' }}">
                            {{ ($bulletin->average >= 10) ? 'ADMIS(E)' : 'AJOURNÉ(E)' }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <div class="comments">
            <h4>Commentaires du professeur principal:</h4>
            <p>{{ $bulletin->teacher_comments ?? 'Aucun commentaire.' }}</p>

            <h4>Commentaires du directeur:</h4>
            <p>{{ $bulletin->principal_comments ?? 'Aucun commentaire.' }}</p>
        </div>

        <div class="signature" style="margin-top: 30px; display: flex; justify-content: space-between;">
            <div class="signature-box">
                <p>Le Professeur Principal</p>
            </div>
            <div class="signature-box">
                <p>Les Parents</p>
            </div>
            <div class="signature-box">
                <p>Le Directeur</p>
            </div>
        </div>

        @if($includeFooter)
        <div class="footer">
            <p>Ce bulletin est un document officiel. Toute modification non autorisée constitue un faux et usage de faux passible de poursuites.</p>
            <p>© {{ date('Y') }} Établissement Scolaire - Tous droits réservés</p>
        </div>
        @endif

        @if(!$loop->last)
        <div class="page-break"></div>
        @endif
    @endforeach
</body>
</html>
