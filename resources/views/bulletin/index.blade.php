@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-journal-text"></i> Gestion des Bulletins</h5>
            <div>
                <a href="{{ route('bulletins.generate') }}" class="btn btn-light">
                    <i class="bi bi-file-earmark-plus"></i> Générer des Bulletins
                </a>
            </div>
        </div>

        <div class="card-body">
            <!-- Filtres avancés -->
            <form action="{{ route('bulletins.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un élève..." value="{{ request('search') }}">
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
                    <select name="class_room_id" class="form-select">
                        <option value="">Toutes les classes</option>
                        @foreach($classRooms as $classRoom)
                            <option value="{{ $classRoom->id }}" {{ request('class_room_id') == $classRoom->id ? 'selected' : '' }}>
                                {{ $classRoom->name }}
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
                    <select name="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filtrer
                    </button>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('bulletins.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Statistiques des bulletins -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Total Bulletins</h6>
                                    <h3 class="mb-0">{{ $totalBulletins }}</h3>
                                </div>
                                <i class="bi bi-file-earmark-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">En attente</h6>
                                    <h3 class="mb-0">{{ $pendingBulletins }}</h3>
                                </div>
                                <i class="bi bi-hourglass-split fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Publiés</h6>
                                    <h3 class="mb-0">{{ $publishedBulletins }}</h3>
                                </div>
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Téléchargements</h6>
                                    <h3 class="mb-0">{{ $totalDownloads }}</h3>
                                </div>
                                <i class="bi bi-cloud-download fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions groupées -->
            <div class="mb-3 d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="bulkActionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear"></i> Actions groupées
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="bulkActionDropdown">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkGenerateModal">
                                <i class="bi bi-file-earmark-plus"></i> Génération par classe
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkPublishModal">
                                <i class="bi bi-check-all"></i> Publier sélectionnés
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#bulkExportModal">
                                <i class="bi bi-file-pdf"></i> Export PDF par lot
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('bulletins.print_all') }}" class="btn btn-outline-danger">
                    <i class="bi bi-printer"></i> Imprimer tous les bulletins filtrés
                </a>
            </div>

            <!-- Tableau des bulletins -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>Élève</th>
                            <th>Classe</th>
                            <th>Trimestre</th>
                            <th>Année</th>
                            <th>Moyenne</th>
                            <th>Rang</th>
                            <th>Statut</th>
                            <th>Généré le</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bulletins as $bulletin)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input bulletin-checkbox" type="checkbox" value="{{ $bulletin->id }}">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2 bg-light rounded-circle">
                                        <span class="avatar-text">{{ substr($bulletin->student->first_name, 0, 1) }}{{ substr($bulletin->student->last_name, 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</div>
                                        <div class="small text-muted">ID: {{ $bulletin->student->student_id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $bulletin->classRoom->name }}</td>
                            <td><span class="badge bg-info">Trimestre {{ $bulletin->trimester }}</span></td>
                            <td>{{ $bulletin->academicYear->year }}</td>
                            <td>
                                @if($bulletin->average)
                                    <span class="fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                                        {{ $bulletin->average }}/20
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($bulletin->rank)
                                    <span class="badge {{ $bulletin->rank <= 3 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $bulletin->rank }}<sup>e</sup>/{{ $totalStudentsInClass[$bulletin->class_room_id] ?? 0 }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($bulletin->status == 'draft')
                                    <span class="badge bg-secondary">Brouillon</span>
                                @elseif($bulletin->status == 'pending')
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @elseif($bulletin->status == 'published')
                                    <span class="badge bg-success">Publié</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $bulletin->generated_at ? \Carbon\Carbon::createFromTimestamp($bulletin->generated_at)->format('d/m/Y H:i') : '-' }}
                                </small>

                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('bulletins.show', $bulletin) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    {{-- <a href="{{ route('bulletins.edit', $bulletin) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a> --}}
                                    <a href="{{ route('bulletins.pdf', $bulletin) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-file-pdf"></i></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="confirmDelete('{{ $bulletin->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $bulletin->id }}" action="{{ route('bulletins.destroy', $bulletin) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-4">
                                <div class="empty-state">
                                    <i class="bi bi-journal-x display-4 text-muted mb-3"></i>
                                    <h6>Aucun bulletin trouvé</h6>
                                    <p class="text-muted">Aucun bulletin ne correspond à vos critères de recherche.</p>
                                    <a href="{{ route('bulletins.generate') }}" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle"></i> Générer des bulletins
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $bulletins->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Génération par classe -->
<div class="modal fade" id="bulkGenerateModal" tabindex="-1" aria-labelledby="bulkGenerateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bulletins.generate_by_class') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="bulkGenerateModalLabel">Génération des bulletins par classe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="genClassRoom" class="form-label">Classe</label>
                        <select name="class_room_id" id="genClassRoom" class="form-select" required>
                            <option value="">-- Sélectionner une classe --</option>
                            @foreach($classRooms as $classRoom)
                                <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
                                @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="genAcademicYear" class="form-label">Année académique</label>
                        <select name="academic_year_id" id="genAcademicYear" class="form-select" required>
                            <option value="">-- Sélectionner une année --</option>
                            @foreach($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}">{{ $academicYear->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="genTrimester" class="form-label">Trimestre</label>
                        <select name="trimester" id="genTrimester" class="form-select" required>
                            <option value="">-- Sélectionner un trimestre --</option>
                            <option value="1">Trimestre 1</option>
                            <option value="2">Trimestre 2</option>
                            <option value="3">Trimestre 3</option>
                        </select>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="autoPublish" name="auto_publish" value="1">
                        <label class="form-check-label" for="autoPublish">
                            Publier automatiquement les bulletins
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-file-earmark-plus"></i> Générer les bulletins
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Publication en masse -->
<div class="modal fade" id="bulkPublishModal" tabindex="-1" aria-labelledby="bulkPublishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bulletins.publish_selected') }}" method="POST" id="publishForm">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="bulkPublishModalLabel">Publication des bulletins sélectionnés</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Vous allez publier les bulletins sélectionnés. Les bulletins publiés seront accessibles aux élèves et parents.
                    </div>
                    <div id="selectedBulletinsContainer">
                        <p class="text-muted">Aucun bulletin sélectionné.</p>
                    </div>
                    <input type="hidden" name="bulletin_ids" id="selectedBulletinsInput">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success" id="publishButton" disabled>
                        <i class="bi bi-check-circle"></i> Publier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Export PDF en masse -->
<div class="modal fade" id="bulkExportModal" tabindex="-1" aria-labelledby="bulkExportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bulletins.export_selected') }}" method="POST" id="exportForm">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="bulkExportModalLabel">Export PDF des bulletins sélectionnés</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="selectedBulletinsExportContainer">
                        <p class="text-muted">Aucun bulletin sélectionné.</p>
                    </div>
                    <input type="hidden" name="bulletin_ids" id="selectedBulletinsExportInput">

                    <div class="form-check mb-3 mt-3">
                        <input class="form-check-input" type="checkbox" id="includeHeader" name="include_header" value="1" checked>
                        <label class="form-check-label" for="includeHeader">
                            Inclure l'en-tête de l'école
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="includeFooter" name="include_footer" value="1" checked>
                        <label class="form-check-label" for="includeFooter">
                            Inclure le pied de page
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger" id="exportButton" disabled>
                        <i class="bi bi-file-pdf"></i> Exporter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la suppression
        window.confirmDelete = function(bulletinId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer ce bulletin ?')) {
                document.getElementById('delete-form-' + bulletinId).submit();
            }
        };

        // Gestion de la sélection des bulletins
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulletinCheckboxes = document.querySelectorAll('.bulletin-checkbox');
        const selectedBulletinsInput = document.getElementById('selectedBulletinsInput');
        const selectedBulletinsExportInput = document.getElementById('selectedBulletinsExportInput');
        const selectedBulletinsContainer = document.getElementById('selectedBulletinsContainer');
        const selectedBulletinsExportContainer = document.getElementById('selectedBulletinsExportContainer');
        const publishButton = document.getElementById('publishButton');
        const exportButton = document.getElementById('exportButton');

        // Sélectionner/Désélectionner tous
        selectAllCheckbox.addEventListener('change', function() {
            bulletinCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedBulletins();
        });

        // Mise à jour individuelle
        bulletinCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectedBulletins();

                // Vérifier si tous les bulletins sont sélectionnés
                const allChecked = Array.from(bulletinCheckboxes).every(cb => cb.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });

        // Mise à jour des bulletins sélectionnés
        function updateSelectedBulletins() {
            const selectedIds = Array.from(bulletinCheckboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            selectedBulletinsInput.value = selectedIds.join(',');
            selectedBulletinsExportInput.value = selectedIds.join(',');

            if (selectedIds.length > 0) {
                selectedBulletinsContainer.innerHTML = `
                    <div class="alert alert-success">
                        <strong>${selectedIds.length}</strong> bulletin(s) sélectionné(s)
                    </div>
                `;
                selectedBulletinsExportContainer.innerHTML = `
                    <div class="alert alert-success">
                        <strong>${selectedIds.length}</strong> bulletin(s) sélectionné(s)
                    </div>
                `;
                publishButton.disabled = false;
                exportButton.disabled = false;
            } else {
                selectedBulletinsContainer.innerHTML = `<p class="text-muted">Aucun bulletin sélectionné.</p>`;
                selectedBulletinsExportContainer.innerHTML = `<p class="text-muted">Aucun bulletin sélectionné.</p>`;
                publishButton.disabled = true;
                exportButton.disabled = true;
            }
        }
    });
</script>
@endpush
@endsection
