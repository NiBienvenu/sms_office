@extends('layouts.app')

@section('title', 'Schedule Management')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar3"></i> Class Schedule</h5>
            <div>
                <div class="dropdown d-inline-block me-2">
                    <button class="btn btn-light dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li><a class="dropdown-item" href="{{ route('schedules.pdf.daily') }}?{{ http_build_query(request()->query()) }}">Daily Schedule</a></li>
                        <li><a class="dropdown-item" href="{{ route('schedules.pdf.weekly') }}?{{ http_build_query(request()->query()) }}">Weekly Schedule</a></li>
                        <li><hr class="dropdown-divider"></li>
                        {{-- <li><a class="dropdown-item" href="{{ route('schedules.pdf.all') }}?{{ http_build_query(request()->query()) }}">Complete Schedule</a></li> --}}
                    </ul>
                </div>
                <a href="{{ route('schedules.weekly.view') }}" class="btn btn btn-outline-light me-2">
                    <i class="bi bi-calendar-week me-1"></i> Weekly View
                </a>
                <a href="{{ route('schedules.create') }}" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Add Schedule
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('schedules.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-book"></i></span>
                        <select name="course" class="form-select">
                            <option value="">All Courses</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                    {{ request('course') == $course->id ? 'selected' : '' }}>
                                    {{ $course->code }} - {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <select name="teacher" class="form-select">
                            <option value="">All Teachers</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ request('teacher') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->fullname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-day"></i></span>
                        <select name="day" class="form-select">
                            <option value="">All Days</option>
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                        <select name="academic_year" class="form-select">
                            <option value="">All Years</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    {{ request('academic_year') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>

            <!-- View Selector Tabs -->
            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <a class="nav-link {{ !request('view') || request('view') == 'list' ? 'active' : '' }}"
                       href="{{ route('schedules.index', array_merge(request()->query(), ['view' => 'list'])) }}">
                        <i class="bi bi-list"></i> List View
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('view') == 'weekly' ? 'active' : '' }}"
                       href="{{ route('schedules.index', array_merge(request()->query(), ['view' => 'weekly'])) }}">
                        <i class="bi bi-calendar-week"></i> Weekly View
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('view') == 'daily' ? 'active' : '' }}"
                       href="{{ route('schedules.index', array_merge(request()->query(), ['view' => 'daily'])) }}">
                        <i class="bi bi-calendar-day"></i> Daily View
                    </a>
                </li>
            </ul>

            @if(!request('view') || request('view') == 'list')
                <!-- List View -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Course</th>
                                <th>Teacher</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Academic Year</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $schedule->course->code }}</span>
                                        <small>{{ $schedule->course->name }}</small>
                                    </div>
                                </td>
                                <td>{{ $schedule->teacher->fullname ?? ''  }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $schedule->day_of_week }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                </td>
                                <td>{{ $schedule->room }}</td>
                                <td>{{ $schedule->academicYear->year }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('schedules.show', $schedule) }}"
                                        class="btn btn-sm btn-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('schedules.edit', $schedule) }}"
                                        class="btn btn-sm btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="confirmDelete('{{ $schedule->id }}')"
                                                title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $schedule->id }}"
                                        action="{{ route('schedules.destroy', $schedule) }}"
                                        method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-calendar-x display-4 d-block"></i>
                                    No schedules found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            @elseif(request('view') == 'weekly')
                <!-- Weekly View -->
                <div class="weekly-schedule mb-4">
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered m-0">
                                    <thead>
                                        <tr class="bg-light">
                                            <th width="8%" class="text-center">Time</th>
                                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                <th width="13%" class="text-center">{{ $day }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($hour = 8; $hour <= 18; $hour++)
                                            <tr>
                                                <td class="text-center align-middle bg-light">
                                                    {{ sprintf('%02d:00', $hour) }}
                                                </td>
                                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <td class="position-relative p-0">
                                                        @php
                                                            $hourSchedules = $schedules->filter(function($schedule) use ($day, $hour) {
                                                                $startHour = (int)substr($schedule->start_time, 0, 2);
                                                                $endHour = (int)substr($schedule->end_time, 0, 2);
                                                                return $schedule->day_of_week === $day &&
                                                                    $startHour <= $hour && $endHour > $hour;
                                                            });
                                                        @endphp

                                                        @foreach($hourSchedules as $schedule)
                                                            @php
                                                                $startHour = (int)substr($schedule->start_time, 0, 2);
                                                                $startMin = (int)substr($schedule->start_time, 3, 2);
                                                                $endHour = (int)substr($schedule->end_time, 0, 2);
                                                                $endMin = (int)substr($schedule->end_time, 3, 2);

                                                                $isStart = $startHour === $hour;
                                                                $isEnd = $endHour === $hour;
                                                            @endphp
                                                            <div class="p-1 mb-1 rounded bg-primary bg-opacity-10 border-start border-primary border-3">
                                                                <small class="d-block fw-bold">{{ $schedule->course->code }}</small>
                                                                <small class="d-block">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</small>
                                                                <small class="d-block">Room: {{ $schedule->room }}</small>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(request('view') == 'daily')
                <!-- Daily View -->
                <div class="row g-4">
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">{{ $day }}</h5>
                                </div>
                                <div class="card-body p-0">
                                    @php
                                        $daySchedules = $schedules->where('day_of_week', $day)->sortBy('start_time');
                                    @endphp

                                    @if($daySchedules->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach($daySchedules as $schedule)
                                                <li class="list-group-item">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <span class="badge bg-primary">
                                                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                                            </span>
                                                            <span class="ms-2">{{ $schedule->course->code }}</span>
                                                        </div>
                                                        <small class="text-muted">Room: {{ $schedule->room }}</small>
                                                    </div>
                                                    <small class="d-block mt-1">{{ $schedule->teacher->fullname ?? 'No teacher assigned' }}</small>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="bi bi-calendar-x text-muted mb-2"></i>
                                            <p class="mb-0">No schedules for {{ $day }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Pagination (only for list view) -->
            @if(!request('view') || request('view') == 'list')
                <div class="d-flex justify-content-end mt-3">
                    {{ $schedules->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(scheduleId) {
    if (confirm('Are you sure you want to delete this schedule?')) {
        document.getElementById('delete-form-' + scheduleId).submit();
    }
}
</script>
@endpush
@endsection
