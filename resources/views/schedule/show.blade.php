@extends('layouts.app')

@section('title', 'Schedule Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Schedule Details</h5>
            <div>
                <div class="dropdown d-inline-block me-2">
                    <button class="btn btn-light dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        {{-- <li><a class="dropdown-item" href="{{ route('schedules.pdf.single', $schedule) }}">Schedule Details</a></li> --}}
                        <li><a class="dropdown-item" href="{{ route('schedules.pdf.daily', ['day' => $schedule->day_of_week]) }}">Daily Schedule ({{ $schedule->day_of_week }})</a></li>
                    </ul>
                </div>
                <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-light me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('schedules.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Course and Teacher Information -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Class Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <h6 class="text-muted">Course</h6>
                                    <p class="mb-0">
                                        <span class="badge bg-primary">{{ $schedule->course->code }}</span><br>
                                        <strong class="mt-2 d-inline-block">{{ $schedule->course->name }}</strong>
                                    </p>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted">Teacher</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($schedule->teacher->fullname, 0, 1)) }}
                                        </div>
                                        <p class="mb-0">{{ $schedule->teacher->fullname }}</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-muted">Academic Year</h6>
                                    <p class="mb-0">
                                        <span class="badge bg-secondary">{{ $schedule->academicYear->year }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Details -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Time and Location</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <h6 class="text-muted">Day</h6>
                                    <p class="mb-0">
                                        <span class="badge bg-info">{{ $schedule->day_of_week }}</span>
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted">Time</h6>
                                    <p class="mb-0">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </p>
                                    <small class="text-muted">
                                        Duration:
                                        @php
                                            $start = \Carbon\Carbon::parse($schedule->start_time);
                                            $end = \Carbon\Carbon::parse($schedule->end_time);
                                            $duration = $end->diffInMinutes($start);
                                            $hours = floor($duration / 60);
                                            $minutes = $duration % 60;
                                            echo $hours > 0 ? "$hours hr " : "";
                                            echo $minutes > 0 ? "$minutes min" : "";
                                        @endphp
                                    </small>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted">Room</h6>
                                    <p class="mb-0">
                                        <i class="bi bi-door-open me-1"></i>
                                        {{ $schedule->room }}
                                    </p>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted">Created/Updated</h6>
                                    <small class="d-block">
                                        <i class="bi bi-calendar-plus me-1"></i>
                                        {{ $schedule->created_at->format('M d, Y') }}
                                    </small>
                                    <small class="d-block">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        {{ $schedule->updated_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visual Timeline -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="schedule-timeline">
                                @php
                                    $startHour = (int)substr($schedule->start_time, 0, 2);
                                    $startMin = (int)substr($schedule->start_time, 3, 2);
                                    $endHour = (int)substr($schedule->end_time, 0, 2);
                                    $endMin = (int)substr($schedule->end_time, 3, 2);

                                    $totalMinutes = ($endHour * 60 + $endMin) - ($startHour * 60 + $startMin);
                                    $widthPercentage = ($totalMinutes / (12 * 60)) * 100; // Assuming 12 hour display (8 AM - 8 PM)
                                    $leftPercentage = ((($startHour - 8) * 60 + $startMin) / (12 * 60)) * 100; // 8 AM is start of timeline
                                @endphp

                                <div class="timeline-container position-relative" style="height: 80px;">
                                    @for($hour = 8; $hour <= 20; $hour++)
                                        <div class="position-absolute" style="left: {{ (($hour - 8) / 12) * 100 }}%;">
                                            <div class="timeline-marker"></div>
                                            <small class="timeline-hour">{{ sprintf('%02d:00', $hour) }}</small>
                                        </div>
                                    @endfor

                                    <div class="position-absolute timeline-event bg-primary text-white rounded p-2"
                                         style="left: {{ $leftPercentage }}%; width: {{ $widthPercentage }}%; top: 20px;">
                                        <small class="d-block fw-bold">{{ $schedule->course->code }}</small>
                                        <small>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Classes (Same Course) -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Other Classes for this Course</h6>
                        </div>
                        <div class="card-body p-0">
                            @php
                                $relatedSchedules = \App\Models\Schedule::where('course_id', $schedule->course_id)
                                    ->where('id', '!=', $schedule->id)
                                    ->take(5)
                                    ->get();
                            @endphp

                            @if($relatedSchedules->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover m-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Day</th>
                                                <th>Time</th>
                                                <th>Teacher</th>
                                                <th>Room</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($relatedSchedules as $related)
                                                <tr>
                                                    <td><span class="badge bg-info">{{ $related->day_of_week }}</span></td>
                                                    <td>{{ \Carbon\Carbon::parse($related->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($related->end_time)->format('H:i') }}</td>
                                                    <td>{{ $related->teacher->fullname }}</td>
                                                    <td>{{ $related->room }}</td>
                                                    <td>
                                                        <a href="{{ route('schedules.show', $related) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="mb-0 text-muted">No other classes for this course</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .timeline-container {
        border-bottom: 2px solid #dee2e6;
        margin-top: 30px;
    }
    .timeline-marker {
        height: 12px;
        width: 2px;
        background-color: #dee2e6;
        margin-bottom: 5px;
    }
    .timeline-hour {
        font-size: 10px;
        color: #6c757d;
        transform: translateX(-50%);
        display: block;
    }
    .timeline-event {
        height: 40px;
        z-index: 10;
    }
</style>
@endpush
@endsection
