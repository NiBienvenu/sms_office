<div class="row g-3">
    <!-- Personal Information -->
    <div class="row g-3">
        <!-- Personal Information -->
        <div class="col-md-12 mb-2">
            <h5 class="border-bottom pb-2"><i class="bi bi-person-circle"></i> Personal Information</h5>
        </div>

        <div class="col-md-4">
            <label for="matricule" class="form-label">
                <i class="bi bi-card-text"></i> Student ID
            </label>
            <input type="text" class="form-control form-control-md @error('matricule') is-invalid @enderror"
                   id="matricule" name="matricule" value="{{ old('matricule', $student->matricule ?? '') }}" required>
            @error('matricule')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="first_name" class="form-label">
                <i class="bi bi-person"></i> First Name
            </label>
            <input type="text" class="form-control form-control-md @error('first_name') is-invalid @enderror"
                   id="first_name" name="first_name" value="{{ old('first_name', $student->first_name ?? '') }}" required>
            @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="last_name" class="form-label">
                <i class="bi bi-person"></i> Last Name
            </label>
            <input type="text" class="form-control form-control-md @error('last_name') is-invalid @enderror"
                   id="last_name" name="last_name" value="{{ old('last_name', $student->last_name ?? '') }}" required>
            @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="email" class="form-label">
                <i class="bi bi-envelope"></i> Email
            </label>
            <input type="email" class="form-control form-control-md @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $student->email ?? '') }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="phone" class="form-label">
                <i class="bi bi-telephone"></i> Phone
            </label>
            <input type="tel" class="form-control form-control-md @error('phone') is-invalid @enderror"
                   id="phone" name="phone" value="{{ old('phone', $student->phone ?? '') }}">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="address" class="form-label">
                <i class="bi bi-geo-alt"></i> Address
            </label>
            <textarea class="form-control form-control-md @error('address') is-invalid @enderror"
                      id="address" name="address" required>{{ old('address', $student->address ?? '') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="gender" class="form-label">
                <i class="bi bi-gender-ambiguous"></i> Gender
            </label>
            <select class="form-select form-select-md @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="birth_date" class="form-label">
                <i class="bi bi-calendar"></i> Birth Date
            </label>
            <input type="date" class="form-control form-control-md @error('birth_date') is-invalid @enderror"
                   id="birth_date" name="birth_date" value="{{ old('birth_date', optional($student)->birth_date?->format('Y-m-d') ?? '') }}" required>
            @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="birth_place" class="form-label">
                <i class="bi bi-geo"></i> Birth Place
            </label>
            <input type="text" class="form-control form-control-md @error('birth_place') is-invalid @enderror"
                   id="birth_place" name="birth_place" value="{{ old('birth_place', $student->birth_place ?? '') }}" required>
            @error('birth_place')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="nationality" class="form-label">
                <i class="bi bi-flag"></i> Nationality
            </label>
            <input type="text" class="form-control form-control-md @error('nationality') is-invalid @enderror"
                   id="nationality" name="nationality" value="{{ old('nationality', $student->nationality ?? '') }}" required>
            @error('nationality')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="blood_group" class="form-label">
                <i class="bi bi-droplet"></i> Blood Group
            </label>
            <input type="text" class="form-control form-control-md @error('blood_group') is-invalid @enderror"
                   id="blood_group" name="blood_group" value="{{ old('blood_group', $student->blood_group ?? '') }}">
            @error('blood_group')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="health_issues" class="form-label">
                <i class="bi bi-heart-pulse"></i> Health Issues
            </label>
            <textarea class="form-control form-control-md @error('health_issues') is-invalid @enderror"
                      id="health_issues" name="health_issues">{{ old('health_issues', $student->health_issues ?? '') }}</textarea>
            @error('health_issues')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="emergency_contact" class="form-label">
                <i class="bi bi-telephone-plus"></i> Emergency Contact
            </label>
            <input type="text" class="form-control form-control-md @error('emergency_contact') is-invalid @enderror"
                   id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $student->emergency_contact ?? '') }}" required>
            @error('emergency_contact')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="academic_year_id" class="form-label">
                <i class="bi bi-calendar-range"></i> Academic Year
            </label>
            <select class="form-select form-select-md @error('academic_year_id') is-invalid @enderror"
                    id="academic_year_id" name="academic_year_id" required>
                <option value="">Select Academic Year</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}"
                            {{ old('academic_year_id', $student->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>
                        {{ $year->year }}
                    </option>
                @endforeach
            </select>
            @error('academic_year_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="previous_school" class="form-label">
                <i class="bi bi-building"></i> Previous School
            </label>
            <input type="text" class="form-control form-control-md @error('previous_school') is-invalid @enderror"
                   id="previous_school" name="previous_school" value="{{ old('previous_school', $student->previous_school ?? '') }}">
            @error('previous_school')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-4">
            <label for="status" class="form-label">
                <i class="bi bi-toggle-on"></i> Status
            </label>
            <select class="form-select form-select-md @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="">Select Status</option>
                <option value="active" {{ old('status', $student->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $student->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <!-- Photo Upload Section -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="photo-preview-container">
                            <img id="photo-preview" src="{{ isset($student) && $student->photo ? asset($student->photo) : asset('images/default-avatar.jpg') }}"
                                 alt="Photo Preview" class="img-thumbnail rounded-circle mx-auto d-block"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <label for="photo" class="form-label">
                            <i class="bi bi-camera"></i> Profile Picture
                        </label>
                        <input type="file" class="form-control form-control-md @error('photo') is-invalid @enderror"
                               id="photo" name="photo" accept="image/*" onchange="previewImage(event)">
                        <div class="form-text">Accepted format: JPG, PNG, GIF (Max. 2MB)</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Information -->
    <div class="col-md-12 mt-4">
        <h5 class="border-bottom pb-2"><i class="bi bi-mortarboard"></i> Academic Information</h5>
    </div>

    <div class="col-md-4">
        <label for="admission_date" class="form-label">
            <i class="bi bi-calendar"></i> Admission Date
        </label>
        <input type="date" class="form-control form-control-md @error('admission_date') is-invalid @enderror"
               id="admission_date" name="admission_date" value="{{ old('admission_date', optional($student)->admission_date?->format('Y-m-d') ?? '') }}" required>
        @error('admission_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="current_class" class="form-label">
            <i class="bi bi-book"></i> Current Class
        </label>

        <select class="form-select form-select-md @error('status') is-invalid @enderror" id="class_room_id" name="class_room_id" required>
            <option value="">Select Class</option>
            @foreach($classrooms as $class)
                <option value="{{ $class->id }}"
                        {{ old('class_room_id', $student->class_room_id ?? '') == $year->id ? 'selected' : '' }}>
                    {{ $class->name }}
                </option>
            @endforeach
        </select>
        @error('class_room_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

    </div>

    <div class="col-md-4">
        <label for="education_level" class="form-label">
            <i class="bi bi-ladder"></i> Education Level
        </label>
        <input type="text" class="form-control form-control-md @error('education_level') is-invalid @enderror"
               id="education_level" name="education_level" value="{{ old('education_level', $student->education_level ?? '') }}" required>
        @error('education_level')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Guardian Information -->
    <div class="col-md-12 mt-4">
        <h5 class="border-bottom pb-2"><i class="bi bi-people"></i> Guardian Information</h5>
    </div>

    <div class="col-md-4">
        <label for="guardian_name" class="form-label">
            <i class="bi bi-person"></i> Guardian's Name
        </label>
        <input type="text" class="form-control form-control-md @error('guardian_name') is-invalid @enderror"
               id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name ?? '') }}" required>
        @error('guardian_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="guardian_address" class="form-label">
            <i class="bi bi-house-door"></i> Guardian Address
        </label>
        <input type="text" class="form-control form-control-md @error('guardian_address') is-invalid @enderror"
               id="guardian_address" name="guardian_address" value="{{ old('guardian_address', $student->guardian_address ?? '') }}" required>
        @error('guardian_address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <div class="col-md-4">
        <label for="guardian_phone" class="form-label">
            <i class="bi bi-telephone"></i> Guardian's Phone
        </label>
        <input type="tel" class="form-control form-control-md @error('guardian_phone') is-invalid @enderror"
               id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone ?? '') }}" required>
        @error('guardian_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="guardian_relationship" class="form-label">
            <i class="bi bi-diagram-3"></i> Relationship
        </label>
        <input type="text" class="form-control form-control-md @error('guardian_relationship') is-invalid @enderror"
               id="guardian_relationship" name="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship ?? '') }}" required>
        @error('guardian_relationship')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="guardian_occupation" class="form-label">
            <i class="bi bi-briefcase"></i> Guardian Occupation
        </label>
        <input type="tel" class="form-control form-control-md @error('guardian_occupation') is-invalid @enderror"
               id="guardian_occupation" name="guardian_occupation" value="{{ old('guardian_occupation', $student->guardian_occupation ?? '') }}" required>
        @error('guardian_occupation')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>


    <!-- Script for image preview -->

</div>
<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const preview = document.getElementById('photo-preview');
            preview.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
