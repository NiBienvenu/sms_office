<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impression des bulletins</title>
    <style>
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                line-height: 1.5;
                color: #333;
            }

            .page-break {
                page-break-after: always;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 1rem;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th, td {
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .school-name {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .bulletin-title {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 10px;
                text-align: center;
            }

            .student-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
            }

            .student-info div {
                width: 48%;
            }

            .grade-good {
                color: green;
                font-weight: bold;
            }

            .grade-bad {
                color: red;
                font-weight: bold;
            }

            .comments {
                margin-top: 15px;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f9f9f9;
            }

            .print-btn {
                display: none;
            }

            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 11px;
                color: #666;
            }
        }

        @media screen {
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                line-height: 1.5;
                color: #333;
                padding: 20px;
                max-width: 1200px;
                margin: 0 auto;
            }

            .print-btn {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 10px 20px;
                background-color: #007bff;
                color: white;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                z-index: 9999;
            }

            .page-break {
                border-top: 2px dashed #ccc;
                margin: 30px 0;
                padding-top: 20px;
            }

            /* Autres styles identiques à ceux de @media print */
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 1rem;
            }

            table, th, td {
                border: 1px solid #ddd;
            }

            th, td {
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            .header {
                text-align: center;
                margin-bottom: 20px;
            }

            .school-name {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .bulletin-title {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 10px;
                text-align: center;
            }

            .student-info {
                display: flex;
                justify-content: space-between;
                margin-bottom: 15px;
            }

            .student-info div {
                width: 48%;
            }

            .grade-good {
                color: green;
                font-weight: bold;
            }

            .grade-bad {
                color: red;
                font-weight: bold;
            }

            .comments {
                margin-top: 15px;
                padding: 10px;
                border: 1px solid #ddd;
                background-color: #f9f9f9;
            }

            .footer {
                margin-top: 20px;
                text-align: center;
                font-size: 11px;
                color: #666;
            }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">Imprimer tous les bulletins</button>

    @foreach($bulletins as $index => $bulletin)
        @if($index > 0)
        <div class="page-break"></div>
        @endif

        <div class="header">
            <div class="school-name">ÉCOLE SECONDAIRE NATIONALE</div>
            <div>Année Scolaire: {{ $bulletin->academicYear->year }}</div>
        </div>

        <div class="bulletin-title">
            BULLETIN DE NOTES - TRIMESTRE {{ $bulletin->trimester }}
        </div>

        <div class="student-info">
            <div>
                <p><strong>Élève:</strong> {{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</p>
                <p><strong>Matricule:</strong> {{ $bulletin->student->student_id }}</p>
                <p><strong>Classe:</strong> {{ $bulletin->classRoom->name }}</p>
            </div>
            <div>
                <p><strong>Date d'émission:</strong> {{ $bulletin->generated_at ? $bulletin->generated_at->format('d/m/Y') : date('d/m/Y') }}</p>
                <p><strong>Moyenne générale:</strong>
                    <span class="{{ $bulletin->average >= 10 ? 'grade-good' : 'grade-bad' }}">
                        {{ $bulletin->average ?? '-' }}/20
                    </span>
                </p>
                <p><strong>Rang:</strong> {{ $bulletin->rank ?? '-' }}</p>
            </div>
        </div>

        @php
            // Récupération des notes de l'élève pour ce trimestre
            $grades = App\Models\Grade::where('student_id', $bulletin->student_id)
                ->where('academic_year_id', $bulletin->academic_year_id)
                ->where('trimester', $bulletin->trimester)
                ->with(['course', 'teacher'])
                ->get();

            // Regroupement par matière
            $gradesByCourse = $grades->groupBy('course_id');
        @endphp

        <table>
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Enseignant</th>
                    <th>Coefficient</th>
                    <th>Notes</th>
                    <th>Moyenne</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gradesByCourse as $courseId => $courseGrades)
                    @php
                        $course = App\Models\Course::find($courseId);
                        $teacher = $courseGrades->first()->teacher;

                        // Calcul de la moyenne pour cette matière
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
                    @endphp

                    <tr>
                        <td>{{ $course->name }}</td>
                        <td>{{ $teacher->last_name }} {{ $teacher->first_name }}</td>
                        <td>{{ $course->coefficient ?? 1 }}</td>
                        <td>
                            @foreach($courseGrades as $grade)
                                {{ $grade->grade_type }}: {{ $grade->percentage }}/20
                                @if(!$loop->last), @endif
                            @endforeach
                        </td>
                        <td class="{{ $average >= 10 ? 'grade-good' : 'grade-bad' }}">
                            {{ number_format($average, 2) }}/20
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="comments">
            <p><strong>Commentaires du professeur principal:</strong></p>
            <p>{{ $bulletin->teacher_comments ?? 'Aucun commentaire' }}</p>

            <p><strong>Commentaires du directeur:</strong></p>
            <p>{{ $bulletin->principal_comments ?? 'Aucun commentaire' }}</p>
        </div>

        <div class="footer">
            <p>Ce bulletin a été généré automatiquement par le système de gestion scolaire.</p>
            <p>© {{ date('Y') }} École Secondaire Nationale - Tous droits réservés</p>
        </div>
    @endforeach

    <script>
        // Auto-print en mode preview
        document.addEventListener('DOMContentLoaded', function() {
            // Attendre un peu pour que la page soit complètement chargée
            setTimeout(function() {
                // window.print();
            }, 1000);
        });
    </script>
</body>
</html>
