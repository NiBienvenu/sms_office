@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Weekly Schedule</h6>
            <div class="d-flex align-items-center">
                <form method="GET" action="{{ route('schedules.weekly.view') }}" class="d-flex align-items-center">
                    <div class="form-group mb-0 me-2">
                        <select name="academic_year" id="academic_year" class="form-select" onchange="this.form.submit()">
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}" {{ $year->id == $academicYearId ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
                <a href="{{ route('schedules.pdf.weekly', ['academic_year' => $academicYearId]) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-download me-1"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="weeklyScheduleTable">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Day/Time</th>
                            @foreach($daysOfWeek as $day)
                                <th>{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $timeSlots = [];
                            $startTime = \Carbon\Carbon::parse('08:00');
                            $endTime = \Carbon\Carbon::parse('18:00');

                            while ($startTime <= $endTime) {
                                $timeSlots[] = $startTime->format('H:i');
                                $startTime->addMinutes(30);
                            }
                        @endphp

                        @foreach($timeSlots as $timeSlot)
                            <tr>
                                <td class="font-weight-bold">{{ $timeSlot }}</td>

                                @foreach($daysOfWeek as $day)
                                    <td>
                                        @foreach($weeklySchedules[$day] as $schedule)
                                            @php
                                                $scheduleStart = \Carbon\Carbon::parse($schedule->start_time);
                                                $scheduleEnd = \Carbon\Carbon::parse($schedule->end_time);
                                                $slotTime = \Carbon\Carbon::parse($timeSlot);
                                                $nextSlot = (clone $slotTime)->addMinutes(30);
                                            @endphp

                                            @if($slotTime->between($scheduleStart, $scheduleEnd, false) ||
                                                ($slotTime->lte($scheduleStart) && $nextSlot->gt($scheduleStart)))
                                                <div class="p-1 mb-1 bg-light border rounded">
                                                    <div class="fw-bold text-primary">{{ $schedule->course->code }}</div>
                                                    <div class="small">{{ $schedule->start_time }} - {{ $schedule->end_time }}</div>
                                                    <div class="small">{{ $schedule->teacher->fullname }}</div>
                                                    <div class="small">Room: {{ $schedule->room }}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
