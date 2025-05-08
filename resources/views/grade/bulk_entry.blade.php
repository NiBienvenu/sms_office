@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-list-check"></i> Saisie groupée des notes</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('grades.store_bulk') }}" method="POST" id="bulk-form">
                @csrf

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="academic_year_id" class="form-label">Année académique</label>
                            <select name="academic_year_id" id="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une année</option>
                                @foreach($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}" {{ old('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
                                        {{ $academicYear->year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('academic_year_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="trimester" class="form-label">Trimestre</label>
                            <select name="trimester" id="trimester" class="form-select @error('trimester') is-invalid @enderror" required>
                                <option value="">Sélectionner un trimestre</option>
                                <option value="1" {{ old('trimester') == '1' ? 'selected' : '' }}>Trimestre 1</option>
                                <option value="2" {{ old('trimester') == '2' ? 'selected' : '' }}>Trimestre 2</option>
                                <option value="3" {{ old('trimester') == '3' ? 'selected' : '' }}>Trimestre 3</option>
                            </select>
                            @error('trimester')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="course_id" class="form-label">Cours</label>
                            <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un cours</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="teacher_id" class="form-label">Enseignant</label>
                            <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un enseignant</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->last_name }} {{ $teacher->first_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('teacher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="grade_type" class="form-label">Type de note</label>
                            <select name="grade_type" id="grade_type" class="form-select @error('grade_type') is-invalid @enderror" required>
                                <option value="">Sélectionner un type</option>
                                <option value="TJ1" {{ old('grade_type') == 'TJ1' ? 'selected' : '' }}>TJ1</option>
                                <option value="TJ2" {{ old('grade_type') == 'TJ2' ? 'selected' : '' }}>TJ2</option>
                                <option value="TJ3" {{ old('grade_type') == 'TJ3' ? 'selected' : '' }}>TJ3</option>
                                <option value="TJ4" {{ old('grade_type') == 'TJ4' ? 'selected' : '' }}>TJ4</option>
                                <option value="EXAM" {{ old('grade_type') == 'EXAM' ? 'selected' : '' }}>Examen</option>
                            </select>
                            @error('grade_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="max_score" class="form-label">Note maximale</label>
                            <input type="number" name="max_score" id="max_score" class="form-control @error('max_score') is-invalid @enderror"
                                value="{{ old('max_score', 20) }}" required step="0.01" min="1">
                            @error('max_score')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="class_id" class="form-label">Classe</label>
                            <select name="class_id" id="class_id" class="form-select @error('class_id') is-invalid @enderror" required>
                                <option value="">Sélectionner une classe</option>
                                @foreach(App\Models\ClassRoom::orderBy('name')->get() as $class)
                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" id="load-students" class="btn btn-info w-100">
                            <i class="bi bi-people"></i> Charger les élèves
                        </button>
                    </div>
                </div>

                <div id="students-container" class="mt-4" style="display: none;">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Saisie des notes par élève</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">N°</th>
                                            <th width="35%">Nom et prénom</th>
                                            <th width="20%">Note</th>
                                            <th width="40%">Commentaire</th>
                                        </tr>
                                    </thead>
                                    <tbody id="students-list">
                                        <!-- Les élèves seront chargés ici dynamiquement -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Enregistrer toutes les notes
                        </button>
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loadStudentsBtn = document.getElementById('load-students');
        const studentsContainer = document.getElementById('students-container');
        const studentsList = document.getElementById('students-list');

        loadStudentsBtn.addEventListener('click', function() {
            const classId = document.getElementById('class_id').value;
            const yearId = document.getElementById('academic_year_id').value;

            if (!classId) {
                alert('Veuillez sélectionner une classe');
                return;
            }

            // Charger les élèves via AJAX
            fetch(`/api/student/byclass?classid=${classId}&yearid=${yearId}`)
                .then(response => response.json())
                .then(students => {
                    studentsList.innerHTML = '';

                    if (students.length === 0) {
                        studentsList.innerHTML = `
                            <tr>
                                <td colspan="4" class="text-center py-3">
                                    Aucun élève trouvé dans cette classe.
                                </td>
                            </tr>
                        `;
                    } else {
                        students.forEach((student, index) => {
                            studentsList.innerHTML += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>
                                        ${student.last_name} ${student.first_name}
                                        <input type="hidden" name="grades[${index}][student_id]" value="${student.id}">
                                    </td>
                                    <td>
                                        <input type="number" name="grades[${index}][score]" class="form-control"
                                            min="0" step="0.01" max="${document.getElementById('max_score').value}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="grades[${index}][comment]" class="form-control">
                                    </td>
                                </tr>
                            `;
                        });
                    }

                    studentsContainer.style.display = 'block';
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors du chargement des élèves');
                });
        });

        // Mettre à jour la note maximale lors du changement de type de note
        document.getElementById('grade_type').addEventListener('change', function() {
            const gradeType = this.value;
            const maxScoreInput = document.getElementById('max_score');

            if (gradeType === 'TJ1' || gradeType === 'TJ3' || gradeType === 'EXAM') {
                maxScoreInput.value = 20;
            } else if (gradeType === 'TJ2' || gradeType === 'TJ4') {
                maxScoreInput.value = 10;
            }
        });
    });
</script>
@endpush
@endsection
