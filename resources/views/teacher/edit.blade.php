@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-pencil-square"></i> Edit Teacher
            </h5>
            <a href="{{ route('teachers.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('teachers.update', $teacher) }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf
                @method('PUT')

                <!-- Personal Information Section -->
                <div class="col-12">
                    <h6 class="text-primary border-bottom pb-2">Personal Information</h6>
                </div>

                <!-- Photo Upload -->
                <div class="col-md-12 mb-4 text-center">
                    <div class="position-relative d-inline-block">
                        <img id="preview"
                             src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : asset('images/default-avatar.png') }}"
                             class="rounded-circle img-thumbnail"
                             style="width: 150px; height: 150px; object-fit: cover;"
                             alt="Teacher photo">
                        <label for="photo" class="position-absolute bottom-0 end-0 mb-2 me-2">
                            <span class="btn btn-sm btn-primary rounded-circle">
                                <i class="bi bi-camera"></i>
                            </span>
                        </label>
                        <input type="file" id="photo" name="photo" class="d-none" accept="image/*">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="employee_id" class="form-label">Employee ID*</label>
                    <input type="text"
                           class="form-control @error('employee_id') is-invalid @enderror"
                           id="employee_id"
                           name="employee_id"
                           value="{{ old('employee_id', $teacher->employee_id) }}"
                           required>
                    @error('employee_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name*</label>
                    <input type="text"
                           class="form-control @error('first_name') is-invalid @enderror"
                           id="first_name"
                           name="first_name"
                           value="{{ old('first_name', $teacher->first_name) }}"
                           required>
                    @error('first_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name*</label>
                    <input type="text"
                           class="form-control @error('last_name') is-invalid @enderror"
                           id="last_name"
                           name="last_name"
                           value="{{ old('last_name', $teacher->last_name) }}"
                           required>
                    @error('last_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="email" class="form-label">Email*</label>
                    <input type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           id="email"
                           name="email"
                           value="{{ old('email', $teacher->email) }}"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="phone" class="form-label">Phone*</label>
                    <input type="tel"
                           class="form-control @error('phone') is-invalid @enderror"
                           id="phone"
                           name="phone"
                           value="{{ old('phone', $teacher->phone) }}"
                           required>
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="birth_date" class="form-label">Birth Date*</label>
                    <input type="date"
                           class="form-control @error('birth_date') is-invalid @enderror"
                           id="birth_date"
                           name="birth_date"
                           value="{{ old('birth_date', $teacher->birth_date->format('Y-m-d')) }}"
                           required>
                    @error('birth_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="nationality" class="form-label">Nationality*</label>
                    <input type="text"
                           class="form-control @error('nationality') is-invalid @enderror"
                           id="nationality"
                           name="nationality"
                           value="{{ old('nationality', $teacher->nationality) }}"
                           required>
                    @error('nationality')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="gender" class="form-label">Gender*</label>
                    <select class="form-select @error('gender') is-invalid @enderror"
                            id="gender"
                            name="gender"
                            required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', $teacher->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $teacher->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $teacher->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="contract_type" class="form-label">Contract Type*</label>
                    <select class="form-select @error('contract_type') is-invalid @enderror"
                            id="contract_type"
                            name="contract_type"
                            required>
                        <option value="">Select Contract Type</option>
                        <option value="full-time" {{ old('contract_type', $teacher->contract_type ?? '') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part-time" {{ old('contract_type', $teacher->contract_type ?? '') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                        <option value="temporary" {{ old('contract_type', $teacher->contract_type ?? '') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                    </select>
                    @error('contract_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="experience_years" class="form-label">Experience Years*</label>
                    <input type="number"
                           class="form-control @error('experience_years') is-invalid @enderror"
                           id="experience_years"
                           name="experience_years"
                           value="{{ old('experience_years', $teacher->experience_years ?? '')  }}"
                           required>
                    @error('experience_years')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="salary_grade" class="form-label">salary Grade*</label>
                    <input type="text"
                           class="form-control @error('salary_grade') is-invalid @enderror"
                           id="salary_grade"
                           name="salary_grade"
                           value="{{ old('salary_grade', $teacher->salary_grade ?? '')  }}"
                           required>
                    @error('salary_grade')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label for="address" class="form-label">Address*</label>
                    <textarea class="form-control @error('address') is-invalid @enderror"
                              id="address"
                              name="address"
                              rows="2"
                              required>{{ old('address', $teacher->address) }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Professional Information Section -->
                <div class="col-12 mt-4">
                    <h6 class="text-primary border-bottom pb-2">Professional Information</h6>
                </div>

                <div class="col-md-4">
                    <label for="department_id" class="form-label">Department*</label>
                    <select class="form-select @error('department_id') is-invalid @enderror"
                            id="department_id"
                            name="department_id"
                            required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id', $teacher->department_id) == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="joining_date" class="form-label">Joining Date*</label>
                    <input type="date"
                           class="form-control @error('joining_date') is-invalid @enderror"
                           id="joining_date"
                           name="joining_date"
                           value="{{ old('joining_date', $teacher->joining_date->format('Y-m-d')) }}"
                           required>
                    @error('joining_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="employment_status" class="form-label">Employment Status*</label>
                    <select class="form-select @error('employment_status') is-invalid @enderror"
                            id="employment_status"
                            name="employment_status"
                            required>
                        <option value="active" {{ old('employment_status', $teacher->employment_status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('employment_status', $teacher->employment_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on-leave" {{ old('employment_status', $teacher->employment_status) == 'on-leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                    @error('employment_status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="position" class="form-label">Position*</label>
                    <input type="text"
                           class="form-control @error('position') is-invalid @enderror"
                           id="position"
                           name="position"
                           value="{{ old('position', $teacher->position) }}"
                           required>
                    @error('position')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="qualification" class="form-label">Qualification*</label>
                    <input type="text"
                           class="form-control @error('qualification') is-invalid @enderror"
                           id="qualification"
                           name="qualification"
                           value="{{ old('qualification', $teacher->qualification) }}"
                           required>
                    @error('qualification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="specialization" class="form-label">Specialization*</label>
                    <input type="text"
                           class="form-control @error('specialization') is-invalid @enderror"
                           id="specialization"
                           name="specialization"
                           value="{{ old('specialization', $teacher->specialization) }}"
                           required>
                    @error('specialization')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Emergency Contact Section -->
                <div class="col-12 mt-4">
                    <h6 class="text-primary border-bottom pb-2">Emergency Contact</h6>
                </div>

                <div class="col-md-6">
                    <label for="emergency_contact_name" class="form-label">Contact Name*</label>
                    <input type="text"
                           class="form-control @error('emergency_contact_name') is-invalid @enderror"
                           id="emergency_contact_name"
                           name="emergency_contact_name"
                           value="{{ old('emergency_contact_name', $teacher->emergency_contact_name) }}"
                           required>
                    @error('emergency_contact_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="emergency_contact_phone" class="form-label">Contact Phone*</label>
                    <input type="tel"
                           class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                           id="emergency_contact_phone"
                           name="emergency_contact_phone"
                           value="{{ old('emergency_contact_phone', $teacher->emergency_contact_phone) }}"
                           required>
                    @error('emergency_contact_phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-save"></i> Update Teacher
                    </button>
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview uploaded image
    document.getElementById('photo').addEventListener('change', function(e) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(e.target.files[0]);
    });
</script>
@endpush
@endsection
