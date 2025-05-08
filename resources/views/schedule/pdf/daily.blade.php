<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emploi du temps - {{ $day }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
        }
        h1 {
            color: #3490dc;
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3490dc;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10px;
            color: #666;
        }
        .time {
            font-weight: bold;
        }
        .course-code {
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 3px 6px;
            background-color: #3490dc;
            color: white;
            border-radius: 4px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h1>Emploi du temps - {{ $day }} (Année académique: {{ $academicYear }})</h1>

    @if($schedules->isEmpty())
        <p style="text-align: center; color: #666;">Aucun cours programmé pour cette période.</p>
    @else
        @php
            // Regrouper par jour si "Tous les jours" est sélectionné
            $groupedSchedules = $schedules->groupBy('day_of_week');
        @endphp

        @foreach($groupedSchedules as $day => $daySchedules)
            <h2>{{ $day }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Horaire</th>
                        <th>Cours</th>
                        <th>Enseignant</th>
                        <th>Salle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($daySchedules as $schedule)
                        <tr>
                            <td class="time">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </td>
                            <td>
                                <span class="course-code">{{ $schedule->course->code }}</span><br>
                                {{ $schedule->course->name }}
                            </td>
                            <td>{{ $schedule->teacher->fullname }}</td>
                            <td><span class="badge">{{ $schedule->room }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
    @endif

    <div class="footer">
        <p>Document généré le {{ date('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
