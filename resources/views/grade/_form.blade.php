@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-journal-plus"></i> Saisie de Note</h5>
            {{-- <span class="badge bg-light text-primary">Année scolaire: {{ $currentAcademicYear->year }}</span> --}}
        </div>

        <div class="card-body">
            <form action="{{ route('grades.store') }}" method="POST" id="gradeForm">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bi bi-search"></i> Recherche d'Élève
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <input type="text"
                                           id="student_search"
                                           class="form-control"
                                           placeholder="Rechercher par matricule ou nom"
                                           autocomplete="off">
                                    <label for="student_search">Rechercher par matricule ou nom</label>
                                </div>

                                <div id="search-results" class="list-group mb-3 d-none">
                                    <!-- Les résultats de recherche seront insérés ici dynamiquement -->
                                </div>

                                <div id="student-info" class="d-none">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Informations de l'Élève</h6>
                                            <button type="button" id="change-student" class="btn btn-sm btn-light">
                                                <i class="bi bi-arrow-repeat"></i> Changer
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="text-center mb-3">
                                                <img id="student-photo" src="" alt="Photo de l'étudiant"
                                                    class="rounded-circle border border-3 border-primary"
                                                    width="100" height="100"
                                                    style="object-fit: cover; display: none;">
                                                <div id="student-initials"
                                                    class="rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white mx-auto"
                                                    style="width: 100px; height: 100px; font-size: 2.5rem; font-weight: bold;">
                                                </div>
                                            </div>
                                            <input type="hidden" name="student_id" id="selected-student-id">
                                            <div class="mb-2">
                                                <span class="text-muted">Nom complet:</span>
                                                <h5 id="student-name" class="mb-1"></h5>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <span class="text-muted">Classe:</span>
                                                    <p id="student-class" class="mb-0 fw-bold"></p>
                                                </div>
                                                <div>
                                                    <span class="text-muted">Matricule:</span>
                                                    <p id="student-matricule" class="mb-0 fw-bold"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card border-primary">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bi bi-clipboard-data"></i> Détails de la Note
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="course_id"
                                                    id="course_id"
                                                    class="form-select @error('course_id') is-invalid @enderror"
                                                    required>
                                                <option value="">Sélectionner</option>
                                                @foreach($courses as $course)
                                                    <option value="{{ $course->id }}">
                                                        {{ $course->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="course_id">Cours</label>
                                            @error('course_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="trimester"
                                                    id="trimester"
                                                    class="form-select @error('trimester') is-invalid @enderror"
                                                    required>
                                                <option value="">Sélectionner</option>
                                                <option value="1">Trimestre 1</option>
                                                <option value="2">Trimestre 2</option>
                                                <option value="3">Trimestre 3</option>
                                            </select>
                                            <label for="trimester">Trimestre</label>
                                            @error('trimester')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="grade_type"
                                                    id="grade_type"
                                                    class="form-select @error('grade_type') is-invalid @enderror"
                                                    required>
                                                <option value="">Sélectionner</option>
                                                <option value="TJ1">Travail journalier 1</option>
                                                <option value="TJ2">Travail journalier 2</option>
                                                <option value="TJ3">Travail journalier 3</option>
                                                <option value="TJ4">Travail journalier 4</option>
                                                <option value="EXAM">Examen</option>
                                            </select>
                                            <label for="grade_type">Type de Note</label>
                                            @error('grade_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <select name="teacher_id"
                                                    id="teacher_id"
                                                    class="form-select @error('teacher_id') is-invalid @enderror"
                                                    required>
                                                <option value="">Sélectionner</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">
                                                        {{ $teacher->last_name }} {{ $teacher->first_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label for="teacher_id">Enseignant</label>
                                            @error('teacher_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number"
                                                   name="score"
                                                   id="score"
                                                   class="form-control @error('score') is-invalid @enderror"
                                                   required
                                                   step="0.01"
                                                   min="0">
                                            <label for="score">Note Obtenue</label>
                                            @error('score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="number"
                                                   name="max_score"
                                                   id="max_score"
                                                   class="form-control @error('max_score') is-invalid @enderror"
                                                   required
                                                   step="0.01"
                                                   min="1"
                                                   value="20">
                                            <label for="max_score">Note Maximale</label>
                                            @error('max_score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-floating">
                                            <textarea name="comment"
                                                      id="comment"
                                                      class="form-control @error('comment') is-invalid @enderror"
                                                      style="height: 100px"></textarea>
                                            <label for="comment">Commentaire</label>
                                            @error('comment')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="alert alert-info">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div id="grade-percentage" class="display-6 mb-2">-- %</div>
                                                    <div id="grade-message">Saisissez une note pour voir le pourcentage</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- <input type="hidden" name="academic_year_id" value="{{ $currentAcademicYear->id }}"> --}}

                <div class="mt-4 d-flex justify-content-between">
                    <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour à la liste
                    </a>
                    <div>
                        <button type="button" class="btn btn-primary me-2" id="preview-btn">
                            <i class="bi bi-eye"></i> Aperçu
                        </button>
                        <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                            <i class="bi bi-save"></i> Enregistrer la Note
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal d'aperçu -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="previewModalLabel">Aperçu de la note</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center mb-4">Récapitulatif</h5>

                            <table class="table table-borderless">
                                <tr>
                                    <th>Élève :</th>
                                    <td id="preview-student"></td>
                                </tr>
                                <tr>
                                    <th>Classe :</th>
                                    <td id="preview-class"></td>
                                </tr>
                                <tr>
                                    <th>Cours :</th>
                                    <td id="preview-course"></td>
                                </tr>
                                <tr>
                                    <th>Type :</th>
                                    <td id="preview-type"></td>
                                </tr>
                                <tr>
                                    <th>Trimestre :</th>
                                    <td id="preview-trimester"></td>
                                </tr>
                                <tr>
                                    <th>Note :</th>
                                    <td>
                                        <span id="preview-score" class="fw-bold"></span>
                                        <span id="preview-percentage" class="badge bg-primary ms-2"></span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <button type="button" class="btn btn-success" id="confirm-btn">Confirmer et Enregistrer</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const studentSearch = document.getElementById('student_search');
    const searchResults = document.getElementById('search-results');
    const studentInfo = document.getElementById('student-info');
    const changeStudentBtn = document.getElementById('change-student');
    const scoreInput = document.getElementById('score');
    const maxScoreInput = document.getElementById('max_score');
    const gradePercentage = document.getElementById('grade-percentage');
    const gradeMessage = document.getElementById('grade-message');
    const submitBtn = document.getElementById('submit-btn');
    const previewBtn = document.getElementById('preview-btn');
    const confirmBtn = document.getElementById('confirm-btn');
    const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));

    // Fonction pour rechercher un étudiant
    let searchTimeout;

    studentSearch.addEventListener('input', function() {
        const query = this.value.trim();

        clearTimeout(searchTimeout);

        if (query.length < 2) {
            searchResults.classList.add('d-none');
            return;
        }

        searchTimeout = setTimeout(() => {
            // AJAX call to search students
            fetch(`/api/student/search?matricule=${query}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);

                searchResults.innerHTML = '';

                if (data.length > 0) {
                    data.forEach(student => {
                        const item = document.createElement('button');
                        item.type = 'button';
                        item.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                        item.innerHTML = `
                            <div>
                                <strong>${student.last_name} ${student.first_name}</strong>
                                <br><small class="text-muted">Matricule: ${student.matricule}</small>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                ${student.class_room ? student.class_room.name : 'Non attribué'}
                            </span>
                        `;

                        item.addEventListener('click', function() {
                            selectStudent(student);
                        });

                        searchResults.appendChild(item);
                    });

                    searchResults.classList.remove('d-none');
                } else {
                    searchResults.innerHTML = `
                        <div class="list-group-item text-center text-danger">
                            <i class="bi bi-exclamation-triangle"></i> Aucun étudiant trouvé
                        </div>
                    `;
                    searchResults.classList.remove('d-none');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }, 300);
    });

    // Fonction pour sélectionner un étudiant
    function selectStudent(student) {
        // Remplir les champs hidden
        document.getElementById('selected-student-id').value = student.id;

        // Afficher les informations de l'étudiant
        document.getElementById('student-name').textContent = `${student.last_name} ${student.first_name}`;
        document.getElementById('student-class').textContent = student.class_room.name;
        document.getElementById('student-matricule').textContent = student.matricule;

        // Afficher la photo ou les initiales
        const studentPhoto = document.getElementById('student-photo');
        const studentInitials = document.getElementById('student-initials');

        if (student.photo) {
            studentPhoto.src = student.photo;
            studentPhoto.style.display = 'block';
            studentInitials.style.display = 'none';
        } else {
            studentPhoto.style.display = 'none';
            const initials = `${student.first_name[0]}${student.last_name[0]}`;
            studentInitials.textContent = initials;
            studentInitials.style.display = 'flex';
        }

        // Masquer les résultats de recherche et afficher les infos
        searchResults.classList.add('d-none');
        studentInfo.classList.remove('d-none');
        studentSearch.value = '';

        // Activer le bouton de soumission
        validateForm();
    }

    // Bouton pour changer d'étudiant
    changeStudentBtn.addEventListener('click', function() {
        studentInfo.classList.add('d-none');
        document.getElementById('selected-student-id').value = '';
        studentSearch.focus();
        validateForm();
    });

    // Calculer le pourcentage de la note
    function updatePercentage() {
        const score = parseFloat(scoreInput.value);
        const maxScore = parseFloat(maxScoreInput.value);

        if (!isNaN(score) && !isNaN(maxScore) && maxScore > 0) {
            const percentage = (score / maxScore) * 100;
            gradePercentage.textContent = `${percentage.toFixed(2)}%`;

            // Message selon le pourcentage
            if (percentage >= 80) {
                gradeMessage.textContent = "Excellent travail!";
                gradePercentage.classList.remove('text-danger', 'text-warning');
                gradePercentage.classList.add('text-success');
            } else if (percentage >= 60) {
                gradeMessage.textContent = "Bon travail";
                gradePercentage.classList.remove('text-danger', 'text-success');
                gradePercentage.classList.add('text-warning');
            } else if (percentage >= 50) {
                gradeMessage.textContent = "Travail satisfaisant";
                gradePercentage.classList.remove('text-success', 'text-danger');
                gradePercentage.classList.add('text-warning');
            } else {
                gradeMessage.textContent = "Des efforts supplémentaires sont nécessaires";
                gradePercentage.classList.remove('text-success', 'text-warning');
                gradePercentage.classList.add('text-danger');
            }
        } else {
            gradePercentage.textContent = "-- %";
            gradeMessage.textContent = "Saisissez une note valide pour voir le pourcentage";
            gradePercentage.classList.remove('text-success', 'text-warning', 'text-danger');
        }
    }

    scoreInput.addEventListener('input', updatePercentage);
    maxScoreInput.addEventListener('input', updatePercentage);

    // Validation du formulaire
    function validateForm() {
        const studentId = document.getElementById('selected-student-id').value;
        const courseId = document.getElementById('course_id').value;
        const trimester = document.getElementById('trimester').value;
        const gradeType = document.getElementById('grade_type').value;
        const teacherId = document.getElementById('teacher_id').value;
        const score = scoreInput.value;
        const maxScore = maxScoreInput.value;

        if (studentId && courseId && trimester && gradeType && teacherId && score && maxScore) {
            submitBtn.disabled = false;
            previewBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
            previewBtn.disabled = true;
        }
    }

    document.querySelectorAll('#gradeForm select, #gradeForm input').forEach(element => {
        element.addEventListener('change', validateForm);
        element.addEventListener('input', validateForm);
    });

    // Aperçu avant soumission
    previewBtn.addEventListener('click', function() {
        const studentName = document.getElementById('student-name').textContent;
        const studentClass = document.getElementById('student-class').textContent;
        const courseSelect = document.getElementById('course_id');
        const courseName = courseSelect.options[courseSelect.selectedIndex].text;
        const typeSelect = document.getElementById('grade_type');
        const typeName = typeSelect.options[typeSelect.selectedIndex].text;
        const trimesterSelect = document.getElementById('trimester');
        const trimesterName = `Trimestre ${trimesterSelect.value}`;
        const score = scoreInput.value;
        const maxScore = maxScoreInput.value;
        const percentage = ((score / maxScore) * 100).toFixed(2);

        document.getElementById('preview-student').textContent = studentName;
        document.getElementById('preview-class').textContent = studentClass;
        document.getElementById('preview-course').textContent = courseName;
        document.getElementById('preview-type').textContent = typeName;
        document.getElementById('preview-trimester').textContent = trimesterName;
        document.getElementById('preview-score').textContent = `${score} / ${maxScore}`;
        document.getElementById('preview-percentage').textContent = `${percentage}%`;

        previewModal.show();
    });

    // Confirmation et soumission du formulaire
    confirmBtn.addEventListener('click', function() {
        document.getElementById('gradeForm').submit();
    });

    // Fermer les résultats lors d'un clic à l'extérieur
    document.addEventListener('click', function(event) {
        if (!studentSearch.contains(event.target) && !searchResults.contains(event.target)) {
            searchResults.classList.add('d-none');
        }
    });
});
</script>
@endpush
