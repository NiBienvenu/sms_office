<div class="row g-4">
    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Schedule Type</h6>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="weeklyScheduleToggle" name="weekly_schedule">
                    <label class="form-check-label" for="weeklyScheduleToggle">Create Weekly Schedule</label>
                </div>
            </div>
        </div>
    </div>
    <!-- Course and Teacher Selection -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Class Assignment</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-book"></i></span>
                            <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id', $schedule->course_id ?? '') == $course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="teacher_id" class="form-label">Teacher <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Select Teacher</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ old('teacher_id', $schedule->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->fullname }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-year"></i></span>
                            <select name="academic_year_id" id="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}"
                                        {{ old('academic_year_id', $schedule->academic_year_id ?? '') == $academicYear->id ? 'selected' : '' }}>
                                        {{ $academicYear->year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule Timing -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Schedule Timing</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Single Day Schedule -->
                    <div id="singleDaySchedule" class="col-md-4">
                        <label for="day_of_week" class="form-label">Day <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-day"></i></span>
                            <select name="day_of_week" id="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror">
                                <option value="">Select Day</option>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <option value="{{ $day }}"
                                        {{ old('day_of_week', $schedule->day_of_week ?? '') == $day ? 'selected' : '' }}>
                                        {{ $day }}
                                    </option>
                                @endforeach
                            </select>
                            @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Multiple Days Schedule -->
                    <div id="multiDaySchedule" class="col-md-12 d-none">
                        <label class="form-label">Days <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="days[]" value="{{ $day }}" id="day_{{ $day }}">
                                    <label class="form-check-label" for="day_{{ $day }}">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('days')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                id="start_time" name="start_time"
                                value="{{ old('start_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock-fill"></i></span>
                            <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                id="end_time" name="end_time"
                                value="{{ old('end_time', isset($schedule) ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Location</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <label for="room" class="form-label">Room <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-door-open"></i></span>
                            <input type="text" class="form-control @error('room') is-invalid @enderror"
                                id="room" name="room" placeholder="Enter room number or name"
                                value="{{ old('room', $schedule->room ?? '') }}" required>
                            @error('room')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Display conflict errors if any -->
    @error('conflict')
        <div class="col-md-12">
            <div class="alert alert-danger">
                {{ $message }}
            </div>
        </div>
    @enderror


</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const weeklyToggle = document.getElementById('weeklyScheduleToggle');
        const singleDaySchedule = document.getElementById('singleDaySchedule');
        const multiDaySchedule = document.getElementById('multiDaySchedule');

        weeklyToggle.addEventListener('change', function() {
            if (this.checked) {
                singleDaySchedule.classList.add('d-none');
                multiDaySchedule.classList.remove('d-none');
                document.getElementById('day_of_week').removeAttribute('required');
            } else {
                singleDaySchedule.classList.remove('d-none');
                multiDaySchedule.classList.add('d-none');
                document.getElementById('day_of_week').setAttribute('required', 'required');
            }
        });
    });
</script>
@endpush
