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
                        <label for="code" class="form-label">Subject Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror"
                            value="{{ old('code', $subject->code ?? '') }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $subject->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $subject->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    {{ old('academic_year_id', $subject->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description', $subject->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Status</h6>
            </div>
            <div class="card-body">
                <div class="form-check form-switch">
                    <input type="checkbox" name="status" id="status" class="form-check-input" value="active"
                        {{ old('status', $subject->status ?? '') == 'active' ? 'checked' : '' }}>
                    <label for="status" class="form-check-label">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>
