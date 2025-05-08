{{-- _form.blade.php --}}
<div class="row g-4">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="form-label">Department Name <span class="text-danger">*</span></label>
            <input type="text" name="name" id="name"
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $department->name ?? '') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="code" class="form-label">Department Code <span class="text-danger">*</span></label>
            <input type="text" name="code" id="code"
                class="form-control @error('code') is-invalid @enderror"
                value="{{ old('code', $department->code ?? '') }}" required>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="4"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $department->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="head_id" class="form-label">Head Teacher</label>
            <select name="head_id" id="head_id"
                class="form-select @error('head_id') is-invalid @enderror">
                <option value="">Select Head Teacher</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}"
                        {{ old('head_id', $department->head_id ?? '') == $teacher->id ? 'selected' : '' }}>
                        {{ $teacher->fullname }}
                    </option>
                @endforeach
            </select>
            @error('head_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
            <select name="academic_year_id" id="academic_year_id"
                class="form-select @error('academic_year_id') is-invalid @enderror" required>
                <option value="">Select Academic Year</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}"
                        {{ old('academic_year_id', $department->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>
                        {{ $year->year }}
                    </option>
                @endforeach
            </select>
            @error('academic_year_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status"
                class="form-select @error('status') is-invalid @enderror">
                <option value="active" {{ old('status', $department->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $department->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> {{ isset($department) ? 'Update' : 'Create' }} Department
            </button>
        </div>
    </div>
</div>
