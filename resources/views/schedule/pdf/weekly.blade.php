<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Emploi du temps hebdomadaire - {{ $academicYear }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
        }
        h1 {
            color: #3490dc;
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
            height: 40px;
            overflow: hidden;
            vertical-align: top;
        }
        th {
            background-color: #3490dc;
            color: white;
        }
        .time-header {
            width: 10%;
            background-color: #3490dc;
            color: white;
            font-weight: bold;
        }
        .day-header {
            background-color: #3490dc;
            color: white;
        }
        .course-cell {
            background-color: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 4px;
            padding: 2px;
            margin-bottom: 2px;
            font-size: 9px;
        }
        .course-code {
            font-weight: bold;
            display: block;
        }
        .teacher {
            font-style: italic;
            font-size: 8px;
        }
        .room {
            display: inline-block;
            padding: 1px 3px;
            background-color: #3490dc;
            color: white;
            border-radius: 3px;
            font-size: 8px;
            margin-top: 2px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Emploi du temps hebdomadaire - Année académique: {{ $academicYear }}</h1>

    <table>
        <thead>
            <tr>
                <th class="time-header">Heure</th>
                <th class="day-header">Lundi</th>
                <th class="day-header">Mardi</th>
                <th class="day-header">Mercredi</th>
                <th class="day-header">Jeudi</th>
                <th class="day-header">Vendredi</th>
                <th class="day-header">Samedi</th>
                <th class="day-header">Dimanche</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timeSlots as $timeSlot)
                <tr>
                    <td class="time-header">{{ $timeSlot }}</td>

                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <td>
                            @foreach($weeklySchedules[$day] as $schedule)
                                @php
                                    $scheduleStart = \Carbon\Carbon::parse($schedule->start_time);
                                    $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time);
                                    $slotTime = \Carbon\Carbon::parse($timeSlot);
                                    $nextSlot = (clone $slotTime)->addMinutes(30);
                                @endphp

                                @if($scheduleStart <= $slotTime && $scheduleEnd > $slotTime)
                                    <div class="course-cell">
                                        <span class="course-code">{{ $schedule->course->code }}</span>
                                        <span class="teacher">{{ $schedule->teacher->fullname }}</span>
                                        <span class="room">{{ $schedule->room }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Document généré le {{ date('d/m/Y à H:i') }}</p>
    </div>
</body>
</html>
