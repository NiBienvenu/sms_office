@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-file-earmark-plus"></i> Génération des Bulletins</h5>
            <a href="{{ route('bulletins.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- Tab navigation -->
                    <ul class="nav nav-tabs mb-4" id="generationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="class-tab" data-bs-toggle="tab" data-bs-target="#class-panel" type="button" role="tab" aria-controls="class-panel" aria-selected="true">
                                <i class="bi bi-people"></i> Génération par classe
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="individual-tab" data-bs-toggle="tab" data-bs-target="#individual-panel" type="button" role="tab" aria-controls="individual-panel" aria-selected="false">
                                <i class="bi bi-person"></i> Génération individuelle
                            </button>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content" id="generationTabsContent">
                        <!-- Tab 1: Génération par classe -->
                        <div class="tab-pane fade show active" id="class-panel" role="tabpanel" aria-labelledby="class-tab">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('bulletins.generate_by_class') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle"></i> Cette option va générer des bulletins pour tous les élèves de la classe sélectionnée.
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="class_room_id" class="form-label">Classe <span class="text-danger">*</span></label>
                                                <select name="class_room_id" id="class_room_id" class="form-select @error('class_room_id') is-invalid @enderror" required>
                                                    <option value="">-- Sélectionner une classe --</option>
                                                    @foreach($classRooms as $classRoom)
                                                        <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('class_room_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="academic_year_id" class="form-label">Année académique <span class="text-danger">*</span></label>
                                                <select name="academic_year_id" id="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                                    <option value="">-- Sélectionner une année --</option>
                                                    @foreach($academicYears as $academicYear)
                                                        <option value="{{ $academicYear->id }}">{{ $academicYear->year }}</option>
                                                    @endforeach
                                                </select>
                                                @error('academic_year_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="trimester" class="form-label">Trimestre <span class="text-danger">*</span></label>
                                                <select name="trimester" id="trimester" class="form-select @error('trimester') is-invalid @enderror" required>
                                                    <option value="">-- Sélectionner un trimestre --</option>
                                                    <option value="1">Trimestre 1</option>
                                                    <option value="2">Trimestre 2</option>
                                                    <option value="3">Trimestre 3</option>
                                                </select>
                                                @error('trimester')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="auto_publish" name="auto_publish" value="1">
                                                    <label class="form-check-label" for="auto_publish">
                                                        Publier automatiquement les bulletins générés
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mt-4">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-file-earmark-plus"></i> Générer les bulletins
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Génération individuelle -->
                        <div class="tab-pane fade" id="individual-panel" role="tabpanel" aria-labelledby="individual-tab">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{ route('bulletins.store') }}" method="POST">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="alert alert-info">
                                                    <i class="bi bi-info-circle"></i> Cette option va générer un bulletin pour un élève spécifique.
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="student_id" class="form-label">Élève <span class="text-danger">*</span></label>
                                                <select name="student_id" id="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                                                    <option value="">-- Sélectionner un élève --</option>
                                                    @foreach($students as $student)
                                                        <option value="{{ $student->id }}">{{ $student->last_name }} {{ $student->first_name }} ({{ $student->student_id }})</option>
                                                    @endforeach
                                                </select>
                                                @error('student_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12">
                                                <label for="class_room_id_individual" class="form-label">Classe <span class="text-danger">*</span></label>
                                                <select name="class_room_id" id="class_room_id_individual" class="form-select @error('class_room_id') is-invalid @enderror" required>
                                                    <option value="">-- Sélectionner une classe --</option>
                                                    @foreach($classRooms as $classRoom)
                                                        <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('class_room_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="academic_year_id_individual" class="form-label">Année académique <span class="text-danger">*</span></label>
                                                <select name="academic_year_id" id="academic_year_id_individual" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                                    <option value="">-- Sélectionner une année --</option>
                                                    @foreach($academicYears as $academicYear)
                                                        <option value="{{ $academicYear->id }}">{{ $academicYear->year }}</option>
                                                    @endforeach
                                                </select>
                                                @error('academic_year_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label for="trimester_individual" class="form-label">Trimestre <span class="text-danger">*</span></label>
                                                <select name="trimester" id="trimester_individual" class="form-select @error('trimester') is-invalid @enderror" required>
                                                    <option value="">-- Sélectionner un trimestre --</option>
                                                    <option value="1">Trimestre 1</option>
                                                    <option value="2">Trimestre 2</option>
                                                    <option value="3">Trimestre 3</option>
                                                </select>
                                                @error('trimester')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-12 mt-4">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="bi bi-file-earmark-plus"></i> Générer le bulletin
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-populate class when student is selected
        const studentSelect = document.getElementById('student_id');
        const classSelect = document.getElementById('class_room_id_individual');

        if (studentSelect && classSelect) {
            // Cette fonction nécessiterait une API pour récupérer la classe de l'élève
            // Pour l'instant, on laisse l'utilisateur choisir manuellement
            studentSelect.addEventListener('change', function() {
                // Example: Call an API to get student's class
                // fetch('/api/students/' + this.value + '/class')
                //    .then(response => response.json())
                //    .then(data => {
                //        classSelect.value = data.class_room_id;
                //    });
            });
        }
    });
</script>
@endpush
@endsection
