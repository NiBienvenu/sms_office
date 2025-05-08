<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulletin - {{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            line-height: 1.5;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 12px;
        }
        .container {
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #ddd;
        }
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .school-info {
            font-size: 11px;
            color: #555;
        }
        .bulletin-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            padding: 5px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .student-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 3px 5px;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grades-table th, .grades-table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
        }
        .grades-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .course-name {
            text-align: left;
            font-weight: bold;
        }
        .summary-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 3px 5px;
        }
        .comments-box {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .signature-box {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            width: 30%;
            text-align: center;
            border-top: 1px dotted #555;
            padding-top: 5px;
            margin-top: 40px;
        }
        .average-cell {
            font-weight: bold;
        }
        .good-grade {
            color: #28a745;
        }
        .bad-grade {
            color: #dc3545;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- En-tête -->
        <div class="header">
            <div class="school-name">ÉTABLISSEMENT SCOLAIRE</div>
            <div class="school-info">
                Adresse: 123 Rue de l'École<br>
                Téléphone: 01 23 45 67 89 | Email: contact@ecole.fr
            </div>
        </div>

        <!-- Titre du bulletin -->
        <div class="bulletin-title">
            BULLETIN DE NOTES - TRIMESTRE {{ $bulletin->trimester }} - {{ $bulletin->academicYear->year }}
        </div>

        <!-- Informations de l'élève -->
        <div class="student-info">
            <table>
                <tr>
                    <td><strong>Nom & Prénom:</strong> {{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</td>
                    <td><strong>Classe:</strong> {{ $bulletin->classRoom->name }}</td>
                </tr>
                <tr>
                    <td><strong>ID Étudiant:</strong> {{ $bulletin->student->student_id }}</td>
                    <td><strong>Date de génération:</strong> {{ $bulletin->generated_at->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>

        <!-- Tableau des notes par matière -->
        <table class="grades-table">
            <thead>
                <tr>
                    <th style="width: 30%;">Matière</th>
                    <th style="width: 10%;">Coef.</th>
                    <th style="width: 12%;">Moyenne</th>
                    <th style="width: 12%;">Moy. Classe</th>
                    <th style="width: 12%;">Min. Classe</th>
                    <th style="width: 12%;">Max. Classe</th>
                    <th style="width: 12%;">Points</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPoints = 0; $totalCoef = 0; @endphp
                @foreach($courseAverages as $courseId => $data)
                    @php
                        $coef = $data['course']->coefficient ?? 1;
                        $points = $data['average'] * $coef;
                        $totalPoints += $points;
                        $totalCoef += $coef;
                    @endphp
                    <tr>
                        <td class="course-name">{{ $data['course']->name }}</td>
                        <td>{{ $coef }}</td>
                        <td class="{{ $data['average'] >= 10 ? 'good-grade' : 'bad-grade' }}">{{ $data['average'] }}/20</td>
                        <td>{{ $data['class_avg'] }}/20</td>
                        <td>{{ $data['class_min'] }}/20</td>
                        <td>{{ $data['class_max'] }}/20</td>
                        <td>{{ number_format($points, 1) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="average-cell">MOYENNE GÉNÉRALE</td>
                    <td class="average-cell {{ $bulletin->average >= 10 ? 'good-grade' : 'bad-grade' }}">{{ $bulletin->average }}/20</td>
                    <td colspan="2">Moyenne de la classe: {{ $classStats['classAverage'] }}/20</td>
                    <td colspan="2">Rang: {{ $bulletin->rank }}/{{ $classStats['totalStudents'] }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Résumé et statistiques -->
        <div class="summary-box">
            <table class="summary-table">
                <tr>
                    <td><strong>Moyenne générale:</strong> {{ $bulletin->average }}/20</td>
                    <td><strong>Moyenne de la classe:</strong> {{ $classStats['classAverage'] }}/20</td>
                </tr>
                <tr>
                    <td><strong>Rang:</strong> {{ $bulletin->rank }}/{{ $classStats['totalStudents'] }}</td>
                    <td><strong>Meilleure moyenne de la classe:</strong> {{ $classStats['highestAverage'] }}/20</td>
                </tr>
                <tr>
                    <td><strong>Appréciation:</strong>
                        @if($bulletin->average >= 16)
                            Excellent
                        @elseif($bulletin->average >= 14)
                            Très bien
                        @elseif($bulletin->average >= 12)
                            Bien
                        @elseif($bulletin->average >= 10)
                            Assez bien
                        @elseif($bulletin->average >= 8)
                            Passable
                        @else
                            Insuffisant
                        @endif
                    </td>
                    <td><strong>Taux de réussite de la classe:</strong> {{ $classStats['passRate'] }}%</td>
                </tr>
            </table>
        </div>

        <!-- Commentaires -->
        <div class="comments-box">
            <p><strong>Commentaires du professeur principal:</strong></p>
            <p>{{ $bulletin->teacher_comments ?? 'Aucun commentaire.' }}</p>

            <p><strong>Commentaires du directeur:</strong></p>
            <p>{{ $bulletin->principal_comments ?? 'Aucun commentaire.' }}</p>
        </div>

        <!-- Signatures -->
        <div class="signature-box">
            <div class="signature">
                Le Directeur
            </div>
            <div class="signature">
                Le Professeur Principal
            </div>
            <div class="signature">
                Parents/Tuteurs
            </div>
        </div>

        <!-- Pied de page -->
        <div class="footer">
            Ce document est généré automatiquement. Pour toute question, veuillez contacter l'administration.
        </div>
    </div>
</body>
</html>
