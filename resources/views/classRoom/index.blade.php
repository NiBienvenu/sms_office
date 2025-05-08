@extends('layouts.app')

@section('title', 'Gestion des Classes')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-building"></i> Liste des Classes</h5>
            <div class="btn-group">
                <a href="{{ route('class-rooms.create') }}" class="btn btn-light">
                    <i class="bi bi-plus-circle"></i> Nouvelle Classe
                </a>
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-filter"></i> Options
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="bi bi-upload"></i> Importer
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('class-rooms.export') }}">
                        <i class="bi bi-download"></i> Exporter
                    </a></li>
                </ul>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('class-rooms.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Recherche..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="level" class="form-select">
                        <option value="">Tous les Niveaux</option>
                        <option value="L1" {{ request('level') == 'L1' ? 'selected' : '' }}>L1</option>
                        <option value="L2" {{ request('level') == 'L2' ? 'selected' : '' }}>L2</option>
                        <option value="L3" {{ request('level') == 'L3' ? 'selected' : '' }}>L3</option>
                        <option value="M1" {{ request('level') == 'M1' ? 'selected' : '' }}>M1</option>
                        <option value="M2" {{ request('level') == 'M2' ? 'selected' : '' }}>M2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="academic_year" class="form-select">
                        <option value="">Toutes les Années</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}"
                                {{ request('academic_year') == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Filtrer
                    </button>
                </div>
            </form>

            <!-- ClassRooms Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Niveau</th>
                            <th>Année Académique</th>
                            <th>Capacité</th>
                            <th>Étudiants</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classRooms as $classRoom)
                        <tr>
                            <td>{{ $classRoom->code }}</td>
                            <td>{{ $classRoom->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ $classRoom->level }}</span>
                            </td>
                            <td>{{ $classRoom->academicYear?->year }}</td>
                            <td>{{ $classRoom->capacity }}</td>
                            <td>
                                <span class="badge {{ $classRoom->students_count >= $classRoom->capacity ? 'bg-danger' : 'bg-success' }}">
                                    {{ $classRoom->students_count }} / {{ $classRoom->capacity }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('class-rooms.show', $classRoom) }}"
                                       class="btn btn-sm btn-info" title="Détails">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('class-rooms.edit', $classRoom) }}"
                                       class="btn btn-sm btn-warning" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $classRoom->id }}')"
                                            title="Supprimer">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $classRoom->id }}"
                                      action="{{ route('class-rooms.destroy', $classRoom) }}"
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox display-4 d-block"></i>
                                Aucune classe trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $classRooms->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(classRoomId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')) {
        document.getElementById('delete-form-' + classRoomId).submit();
    }
}
</script>
@endpush
@endsection
