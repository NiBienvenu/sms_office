<div class="row g-3">
    <!-- Personal Information -->
    <div class="col-md-12 mb-2">
        <h5 class="border-bottom pb-2"><i class="bi bi-person-circle"></i> Personal Information</h5>
    </div>

    <!-- First Name & Last Name -->
    <div class="col-md-6">
        <label for="first_name" class="form-label">
            <i class="bi bi-person"></i> First Name
        </label>
        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
               id="first_name" name="first_name"
               value="{{ old('first_name', $user->first_name ?? '') }}" required>
        @error('first_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="last_name" class="form-label">
            <i class="bi bi-person"></i> Last Name
        </label>
        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
               id="last_name" name="last_name"
               value="{{ old('last_name', $user->last_name ?? '') }}" required>
        @error('last_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email & Phone -->
    <div class="col-md-6">
        <label for="email" class="form-label">
            <i class="bi bi-envelope"></i> Email Address
        </label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
               id="email" name="email"
               value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="phone" class="form-label">
            <i class="bi bi-telephone"></i> Phone Number
        </label>
        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
               id="phone" name="phone"
               value="{{ old('phone', $user->phone ?? '') }}" required>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Fields (Only show in create form or when specifically editing password) -->
    @if(!isset($user) || isset($editPassword))
    <div class="col-md-6">
        <label for="password" class="form-label">
            <i class="bi bi-lock"></i> Password
        </label>
        <input type="password" class="form-control @error('password') is-invalid @enderror"
               id="password" name="password"
               {{ !isset($user) ? 'required' : '' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">
            <i class="bi bi-lock-fill"></i> Confirm Password
        </label>
        <input type="password" class="form-control"
               id="password_confirmation" name="password_confirmation"
               {{ !isset($user) ? 'required' : '' }}>
    </div>
    @endif

    <!-- Pin Code -->
    <div class="col-md-6">
        <label for="pin_code" class="form-label">
            <i class="bi bi-shield-lock"></i> PIN Code
        </label>
        <input type="text" class="form-control @error('pin_code') is-invalid @enderror"
               id="pin_code" name="pin_code"
               value="{{ old('pin_code', $user->pin_code ?? '') }}">
        @error('pin_code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Gender & Birth Date -->
    <div class="col-md-6">
        <label for="gender" class="form-label">
            <i class="bi bi-gender-ambiguous"></i> Gender
        </label>
        <select class="form-select @error('gender') is-invalid @enderror"
                id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" {{ old('gender', $user->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $user->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
        </select>
        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="birth_date" class="form-label">
            <i class="bi bi-calendar"></i> Birth Date
        </label>
        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
               id="birth_date" name="birth_date"
               value="{{ old('birth_date', optional($user)->birth_date?->format('Y-m-d') ?? '' )}}" required>
        @error('birth_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Address Information -->
    <div class="col-md-12 mt-4">
        <h5 class="border-bottom pb-2"><i class="bi bi-geo-alt"></i> Address Information</h5>
    </div>

    <div class="col-md-12">
        <label for="address" class="form-label">
            <i class="bi bi-house"></i> Address
        </label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="address" name="address" rows="2" required>{{ old('address', $user->address ?? '') }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="city" class="form-label">
            <i class="bi bi-building"></i> City
        </label>
        <input type="text" class="form-control @error('city') is-invalid @enderror"
               id="city" name="city"
               value="{{ old('city', $user->city ?? '') }}" required>
        @error('city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="country" class="form-label">
            <i class="bi bi-globe"></i> Country
        </label>
        <input type="text" class="form-control @error('country') is-invalid @enderror"
               id="country" name="country"
               value="{{ old('country', $user->country ?? '') }}" required>
        @error('country')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Status -->
    <div class="col-md-6">
        <label for="status" class="form-label">
            <i class="bi bi-toggle-on"></i> Status
        </label>
        <select class="form-select @error('status') is-invalid @enderror"
                id="status" name="status" required>
            <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Role Selection -->
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <select class="form-select @error('roles') is-invalid @enderror"
                    id="roles" name="roles[]"  aria-label="Select multiple roles">
                <option value="" disabled {{ empty(old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                    Sélectionnez les rôles
                </option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ in_array($role->id, old('roles', isset($user) ? $user->roles->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <label for="roles">Roles</label>
            @error('roles')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


    <!-- Photo Upload -->
    <div class="col-md-12 mt-4">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <img id="photo-preview"
                             src="{{ isset($user) && $user->photo ? asset($user->photo) : asset('images/default-avatar.jpg') }}"
                             class="img-thumbnail rounded-circle mx-auto d-block"
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <label for="photo" class="form-label">
                            <i class="bi bi-camera"></i> Profile Photo
                        </label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror"
                               id="photo" name="photo" accept="image/*"
                               onchange="previewImage(event)">
                        <div class="form-text">Accepted formats: JPG, PNG, GIF (Max. 2MB)</div>
                        @error('photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
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
