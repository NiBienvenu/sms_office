<div class="row g-3">
    <div class="col-md-6">
        <label for="year" class="form-label">
            <i class="bi bi-calendar-event"></i> Academic Year
        </label>
        <input type="text" class="form-control @error('year') is-invalid @enderror"
               id="year" name="year" value="{{ old('year', $academicYear->year ?? '') }}"
               placeholder="e.g., 2024-2025" required>
        @error('year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="status" class="form-label">
            <i class="bi bi-toggle-on"></i> Status
        </label>
        <select class="form-select @error('status') is-invalid @enderror"
                id="status" name="status" required>
            <option value="active" {{ (old('status', $academicYear->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ (old('status', $academicYear->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="start_date" class="form-label">
            <i class="bi bi-calendar-plus"></i> Start Date
        </label>
        <input type="date" class="form-control @error('start_date') is-invalid @enderror"
               id="start_date" name="start_date"
               value="{{ old('end_date', optional($academicYear)->start_date?->format('Y-m-d') ?? '') }}" required>
      @error('start_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="end_date" class="form-label">
            <i class="bi bi-calendar-minus"></i> End Date
        </label>
        <input type="date" class="form-control @error('end_date') is-invalid @enderror"
               id="end_date" name="end_date"
               value="{{ old('end_date', optional($academicYear)->end_date?->format('Y-m-d') ?? '') }}" required>
        @error('end_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-md-12">
        <div class="form-check">
            <input type="checkbox" class="form-check-input @error('current') is-invalid @enderror"
                   id="current" name="current" value="1"
                   {{ old('current', $academicYear?->current ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="current">
                <i class="bi bi-star-fill text-warning"></i> Set as Current Academic Year
            </label>
            @error('current')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
