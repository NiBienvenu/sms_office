<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle"></i> Informations de Base
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom de la Classe</label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name', $classRoom->name ?? '') }}"
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="code" class="form-label">Code de la Classe</label>
                    <input type="text"
                           name="code"
                           id="code"
                           class="form-control @error('code') is-invalid @enderror"
                           value="{{ old('code', $classRoom->code ?? '') }}"
                           required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="level" class="form-label">Niveau</label>
                    <select name="level"
                            id="level"
                            class="form-select @error('level') is-invalid @enderror"
                            required>
                        <option value="">Sélectionner un niveau</option>
                        <option value="L1" {{ (old('level', $classRoom->level ?? '') == 'L1') ? 'selected' : '' }}>L1</option>
                        <option value="L2" {{ (old('level', $classRoom->level ?? '') == 'L2') ? 'selected' : '' }}>L2</option>
                        <option value="L3" {{ (old('level', $classRoom->level ?? '') == 'L3') ? 'selected' : '' }}>L3</option>
                        <option value="M1" {{ (old('level', $classRoom->level ?? '') == 'M1') ? 'selected' : '' }}>M1</option>
                        <option value="M2" {{ (old('level', $classRoom->level ?? '') == 'M2') ? 'selected' : '' }}>M2</option>
                    </select>
                    @error('level')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-gear"></i> Paramètres Avancés
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="capacity" class="form-label">Capacité</label>
                    <input type="number"
                           name="capacity"
                           id="capacity"
                           class="form-control @error('capacity') is-invalid @enderror"
                           value="{{ old('capacity', $classRoom->capacity ?? 30) }}"
                           min="1"
                           max="200">
                    @error('capacity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="academic_year_id" class="form-label">Année Académique</label>
                    <select name="academic_year_id"
                            id="academic_year_id"
                            class="form-select @error('academic_year_id') is-invalid @enderror"
                            required>
                        <option value="">Sélectionner une année</option>
                        @foreach($academicYears as $academicYear)
                            <option value="{{ $academicYear->id }}"
                                {{ (old('academic_year_id', $classRoom->academic_year_id ?? '') == $academicYear->id) ? 'selected' : '' }}>
                                {{ $academicYear->year }}
                            </option>
                        @endforeach
                    </select>
                    @error('academic_year_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description"
                              id="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3">{{ old('description', $classRoom->description ?? '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>
