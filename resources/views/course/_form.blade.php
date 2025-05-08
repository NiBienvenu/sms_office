{{-- _form.blade.php --}}
<div class="row g-4">
    <div class="row g-4">
        <!-- Basic Information -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Basic Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Course Code <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                                value="{{ old('code', $course->code ?? '') }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">Course Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $course->name ?? '') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                            <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required>
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ old('subject_id', $course->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                            <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $course->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                            <select name="academic_year_id" id="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                <option value="">Select Academic Year</option>
                                @foreach($academicYears as $year)
                                    <option value="{{ $year->id }}" {{ old('academic_year_id', $course->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>
                                        {{ $year->year }}
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

        <!-- Course Details -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Course Details</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="credits" class="form-label">Credits <span class="text-danger">*</span></label>
                            <input type="number" name="credits" id="credits" class="form-control @error('credits') is-invalid @enderror"
                                value="{{ old('credits', $course->credits ?? '') }}" required min="0" step="0.5">
                            @error('credits')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="hours_per_week" class="form-label">Hours per Week <span class="text-danger">*</span></label>
                            <input type="number" name="hours_per_week" id="hours_per_week"
                                class="form-control @error('hours_per_week') is-invalid @enderror"
                                value="{{ old('hours_per_week', $course->hours_per_week ?? '') }}" required min="1">
                            @error('hours_per_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="course_type" class="form-label">Course Type <span class="text-danger">*</span></label>
                            <select name="course_type" id="course_type" class="form-select @error('course_type') is-invalid @enderror" required>
                                <option value="">Select Type</option>
                                <option value="Mandatory" {{ old('course_type', $course->course_type ?? '') == 'Mandatory' ? 'selected' : '' }}>Mandatory</option>
                                <option value="Elective" {{ old('course_type', $course->course_type ?? '') == 'Elective' ? 'selected' : '' }}>Elective</option>
                            </select>
                            @error('course_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                            <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                                <option value="">Select Semester</option>
                                <option value="Fall" {{ old('semester', $course->semester ?? '') == 'Fall' ? 'selected' : '' }}>Fall</option>
                                <option value="Spring" {{ old('semester', $course->semester ?? '') == 'Spring' ? 'selected' : '' }}>Spring</option>
                                <option value="Summer" {{ old('semester', $course->semester ?? '') == 'Summer' ? 'selected' : '' }}>Summer</option>
                            </select>
                            @error('semester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="education_level" class="form-label">Education Level <span class="text-danger">*</span></label>
                                <select name="education_level" id="education_level"
                                    class="form-select @error('education_level') is-invalid @enderror" required>
                                    <option value="">Select Education Level</option>
                                    <option value="Undergraduate" {{ old('education_level', $course->education_level ?? '') == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="Graduate" {{ old('education_level', $course->education_level ?? '') == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    <option value="Doctorate" {{ old('education_level', $course->education_level ?? '') == 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                                </select>
                                @error('education_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assessment_method" class="form-label">Assessment Method <span class="text-danger">*</span></label>
                                <select name="assessment_method" id="assessment_method"
                                    class="form-select @error('assessment_method') is-invalid @enderror" required>
                                    <option value="">Select Assessment Method</option>
                                    <option value="Exam" {{ old('assessment_method', $course->assessment_method ?? '') == 'Exam' ? 'selected' : '' }}>Exam</option>
                                    <option value="Project" {{ old('assessment_method', $course->assessment_method ?? '') == 'Project' ? 'selected' : '' }}>Project</option>
                                    <option value="Mixed" {{ old('assessment_method', $course->assessment_method ?? '') == 'Mixed' ? 'selected' : '' }}>Mixed</option>
                                </select>
                                @error('assessment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="max_students" class="form-label">Maximum Students</label>
                            <input type="number" name="max_students" id="max_students"
                                class="form-control @error('max_students') is-invalid @enderror"
                                value="{{ old('max_students', $course->max_students ?? '') }}" min="1">
                            @error('max_students')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Status -->
                        <div class="col-md-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Status</h6>
                                </div>
                                <div class="card-body ml-4">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" name="status" id="status" class="form-check-input" value="active"
                                            {{ old('status', $course->status ?? '') == 'active' ? 'checked' : '' }} checked>
                                        <label for="status" class="form-check-label">Active</label>
                                    </div>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Additional Information</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $course->description ?? '') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="objectives" class="form-label">Course Objectives</label>
                            <textarea name="objectives" id="objectives" rows="3"
                                class="form-control @error('objectives') is-invalid @enderror">{{ old('objectives', $course->objectives ?? '') }}</textarea>
                            @error('objectives')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="assessment_method" class="form-label">Assessment Method</label>
                            <textarea name="assessment_method" id="assessment_method" rows="3"
                                class="form-control @error('assessment_method') is-invalid @enderror">{{ old('assessment_method', $course->assessment_method ?? '') }}</textarea>
                            @error('assessment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="col-12">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ isset($course) ? 'Update' : 'Create' }} Course
            </button>
        </div>
    </div>
</div>
