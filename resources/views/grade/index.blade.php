@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-journal-check"></i> Gestion des Notes</h5>
            <div>
                <a href="{{ route('grades.create') }}" class="btn btn-light me-2">
                    <i class="bi bi-plus-circle"></i> Nouvelle Note
                </a>
                <a href="{{ route('grades.bulk_entry') }}" class="btn btn-light">
                    <i class="bi bi-list-check"></i> Saisie Groupée
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filtres avancés -->
            <form action="{{ route('grades.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <select name="academic_year_id" class="form-select">
                        <option value="">Toutes les années</option>
                        @foreach($academicYears as $academicYear)
                            <option value="{{ $academicYear->id }}" {{ request('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
                                {{ $academicYear->year }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="course_id" class="form-select">
                        <option value="">Tous les cours</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="trimester" class="form-select">
                        <option value="">Tous les trimestres</option>
                        <option value="1" {{ request('trimester') == '1' ? 'selected' : '' }}>Trimestre 1</option>
                        <option value="2" {{ request('trimester') == '2' ? 'selected' : '' }}>Trimestre 2</option>
                        <option value="3" {{ request('trimester') == '3' ? 'selected' : '' }}>Trimestre 3</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="teacher_id" class="form-select">
                        <option value="">Tous les enseignants</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->last_name }} {{ $teacher->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('grades.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Boutons d'import/export améliorés -->
            <div class="mb-3 d-flex gap-2">
                <!-- Bouton Export -->
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-file-excel"></i> Exporter
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                        <li>
                            <form action="{{ route('grade.export') }}" method="GET">
                                <input type="hidden" name="academic_year_id" value="{{ request('academic_year_id') }}">
                                <input type="hidden" name="course_id" value="{{ request('course_id') }}">
                                <input type="hidden" name="trimester" value="{{ request('trimester') }}">
                                <input type="hidden" name="teacher_id" value="{{ request('teacher_id') }}">
                                <input type="hidden" name="format" value="xlsx">
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-file-excel"></i> Format Excel (.xlsx)
                                </button>
                            </form>
                        </li>
                        <li>
                            <form action="{{ route('grade.export') }}" method="GET">
                                <input type="hidden" name="academic_year_id" value="{{ request('academic_year_id') }}">
                                <input type="hidden" name="course_id" value="{{ request('course_id') }}">
                                <input type="hidden" name="trimester" value="{{ request('trimester') }}">
                                <input type="hidden" name="teacher_id" value="{{ request('teacher_id') }}">
                                <input type="hidden" name="format" value="csv">
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-file-text"></i> Format CSV (.csv)
                                </button>
                            </form>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#exportOptionsModal">
                                <i class="bi bi-gear"></i> Options avancées
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Bouton Import -->
                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="bi bi-file-arrow-up"></i> Importer
                </button>
            </div>

            <!-- Tableau des notes -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Élève</th>
                            <th>Cours</th>
                            <th>Type</th>
                            <th>Note</th>
                            <th>Trimestre</th>
                            <th>Année académique</th>
                            <th>Enseignant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($grades as $grade)
                        <tr>
                            <td>
                                {{ $grade->student->last_name }} {{ $grade->student->first_name }}
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $grade->course->name }}</span>
                            </td>
                            <td>{{ $grade->grade_type }}</td>
                            <td>
                                <strong>{{ $grade->score }} / {{ $grade->max_score }}</strong>
                                <br>
                                <small class="text-muted">{{ number_format($grade->percentage, 2) }}%</small>
                            </td>
                            <td>Trimestre {{ $grade->trimester }}</td>
                            <td>{{ $grade->student->academicYear->year }}</td>
                            <td>
                                {{ $grade->teacher->last_name }} {{ $grade->teacher->first_name }}
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('grades.show', $grade) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('grades.edit', $grade) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $grade->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $grade->id }}"
                                      action="{{ route('grades.destroy', $grade) }}"
                                      method="POST"
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 d-block"></i>
                                Aucune note trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $grades->links() }}
            </div>
        </div>
    </div>
</div>


<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('grade.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="importModalLabel"><i class="bi bi-file-arrow-up"></i> Importation des notes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Instructions d'importation:</h6>
                            <ol class="mb-0">
                                <li>Téléchargez d'abord <a href="{{ route('grade.template') }}" class="alert-link">le modèle Excel</a></li>
                                <li>Remplissez le modèle avec vos données</li>
                                <li>Importez le fichier complété ci-dessous</li>
                            </ol>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="card mb-3">
                            <div class="card-body p-3">
                                <label for="importFile" class="form-label">Fichier Excel ou CSV</label>
                                <input type="file" class="form-control" id="importFile" name="file" accept=".xlsx,.xls,.csv" required>
                                <div id="filePreview" class="d-none mt-3">
                                    <div class="card bg-light">
                                        <div class="card-body p-2">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-file-earmark-spreadsheet text-success fs-4 me-2"></i>
                                                <div>
                                                    <strong id="fileName"></strong>
                                                    <small class="text-muted d-block" id="fileSize"></small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-auto" id="removeFile">
                                                    <i class="bi bi-x"></i> Supprimer
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="importAcademicYear" class="form-label">Année académique</label>
                            <select name="academic_year_id" id="importAcademicYear" class="form-select">
                                <option value="">-- Choisir une année --</option>
                                @foreach($academicYears as $academicYear)
                                    <option value="{{ $academicYear->id }}">{{ $academicYear->year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="importCourse" class="form-label">Cours</label>
                            <select name="course_id" id="importCourse" class="form-select">
                                <option value="">-- Choisir un cours --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 mt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="updateExisting" name="update_existing" value="1">
                            <label class="form-check-label" for="updateExisting">
                                Mettre à jour les notes existantes
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary" id="importSubmit">
                                <i class="bi bi-upload"></i> Importer les notes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Export Options -->
<div class="modal fade" id="exportOptionsModal" tabindex="-1" aria-labelledby="exportOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('grade.export') }}" method="GET">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="exportOptionsModalLabel"><i class="bi bi-gear"></i> Options d'exportation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Ajout des filtres existants comme champs cachés -->
                    <input type="hidden" name="search" value="{{ request('search') }}">

                    <div class="mb-3">
                        <label for="exportFormat" class="form-label">Format du fichier</label>
                        <select class="form-select" id="exportFormat" name="format">
                            <option value="xlsx">Excel (.xlsx)</option>
                            <option value="csv">CSV (.csv)</option>
                            <option value="pdf">PDF (.pdf)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Données à exporter</label>
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="exportCourses" name="include_courses" value="1" checked>
                                    <label class="form-check-label" for="exportCourses">
                                        Inclure les informations des cours
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="exportStudents" name="include_students" value="1" checked>
                                    <label class="form-check-label" for="exportStudents">
                                        Inclure les détails des élèves
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="exportComments" name="include_comments" value="1" checked>
                                    <label class="form-check-label" for="exportComments">
                                        Inclure les commentaires
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Filtres</label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <select name="academic_year_id" class="form-select">
                                    <option value="">Toutes les années</option>
                                    @foreach($academicYears as $academicYear)
                                        <option value="{{ $academicYear->id }}" {{ request('academic_year_id') == $academicYear->id ? 'selected' : '' }}>
                                            {{ $academicYear->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="trimester" class="form-select">
                                    <option value="">Tous les trimestres</option>
                                    <option value="1" {{ request('trimester') == '1' ? 'selected' : '' }}>Trimestre 1</option>
                                    <option value="2" {{ request('trimester') == '2' ? 'selected' : '' }}>Trimestre 2</option>
                                    <option value="3" {{ request('trimester') == '3' ? 'selected' : '' }}>Trimestre 3</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="course_id" class="form-select">
                                    <option value="">Tous les cours</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="teacher_id" class="form-select">
                                    <option value="">Tous les enseignants</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->last_name }} {{ $teacher->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-download"></i> Exporter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(gradeId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer cette note ?')) {
            document.getElementById('delete-form-' + gradeId).submit();
        }
    }

    // Script pour l'aperçu du fichier d'import
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('importFile');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');
        const removeFile = document.getElementById('removeFile');
        const importSubmit = document.getElementById('importSubmit');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                fileName.textContent = file.name;

                // Afficher la taille du fichier
                const sizeInKB = file.size / 1024;
                if (sizeInKB < 1024) {
                    fileSize.textContent = sizeInKB.toFixed(2) + ' KB';
                } else {
                    fileSize.textContent = (sizeInKB / 1024).toFixed(2) + ' MB';
                }

                filePreview.classList.remove('d-none');

                // Vérifier extension de fichier
                const extension = file.name.split('.').pop().toLowerCase();
                if (!['xlsx', 'xls', 'csv'].includes(extension)) {
                    alert('Format de fichier non supporté. Veuillez utiliser un fichier Excel (.xlsx, .xls) ou CSV (.csv)');
                    resetFileInput();
                }
            } else {
                filePreview.classList.add('d-none');
            }
        });

        removeFile.addEventListener('click', function() {
            resetFileInput();
        });

        function resetFileInput() {
            fileInput.value = '';
            filePreview.classList.add('d-none');
        }

        // Validation du formulaire d'import
        document.getElementById('importForm').addEventListener('submit', function(e) {
            const academicYearSelect = document.getElementById('importAcademicYear');
            const courseSelect = document.getElementById('importCourse');

            if (academicYearSelect.value === '') {
                e.preventDefault();
                alert('Veuillez sélectionner une année académique');
                academicYearSelect.focus();
                return false;
            }

            if (courseSelect.value === '') {
                e.preventDefault();
                alert('Veuillez sélectionner un cours');
                courseSelect.focus();
                return false;
            }

            // Afficher un indicateur de chargement
            importSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement en cours...';
            importSubmit.disabled = true;
        });
    });
</script>
@endpush
@endsection
