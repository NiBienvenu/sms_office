@extends('layouts.app')

@section('title', 'Détails de la Classe')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Informations de la Classe</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="card-title">{{ $classRoom->name }}</h4>
                        <p class="card-text text-muted">{{ $classRoom->code }}</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Niveau</strong>
                            <span class="badge bg-info">{{ $classRoom->level }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Année Académique</strong>
                            <span>{{ $classRoom->academicYear?->year }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Capacité</strong>
                            <span>{{ $classRoom->capacity }}</span>
                        </li>
                        <li class="list-group-item">
                            <strong>Description</strong>
                            <p class="text-muted mt-2">
                                {{ $classRoom->description ?? 'Aucune description' }}
                            </p>
                        </li>
                    </ul>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <small class="text-muted">
                        <i class="bi bi-clock"></i>
                        Créé le {{ $classRoom?->created_at?->format('d/m/Y') }}
                    </small>
                    <div class="btn-group">
                        <a href="{{ route('class-rooms.edit', $classRoom) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <button onclick="confirmDelete()" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-pie-chart"></i> Statistiques de la Classe</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-people-fill text-primary fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Total Étudiants</h6>
                                    <span class="text-muted">{{ $studentsCount }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="bi bi-book-half text-success fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Matières</h6>
                                    <span class="text-muted">{{ $coursesCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-people"></i> Liste des Étudiants
                        ({{ $studentsCount }} / {{ $classRoom->capacity }})
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('students.create', ['class_room_id' => $classRoom->id]) }}"
                           class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Ajouter
                        </a>
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('class-rooms.export', $classRoom) }}">
                                <i class="bi bi-download"></i> Exporter
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                    <tr>
                                        <td>{{ $student->matricule }}</td>
                                        <td>{{ $student->last_name }}</td>
                                        <td>{{ $student->first_name }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('students.show', $student) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            Aucun étudiant dans cette classe
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-book"></i> Cours de la Classe</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Nom</th>
                                    <th>Département</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($courses as $course)
                                    <tr>
                                        <td>{{ $course->code }}</td>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->department->name }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            Aucun cours pour cette classe
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette classe ? Cette action est irréversible.')) {
        // Assuming you have a delete form with this ID in your form
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush

@push('forms')
<form id="delete-form"
      action="{{ route('class-rooms.destroy', $classRoom) }}"
      method="POST"
      class="d-none">
    @csrf
    @method('DELETE')
</form>
@endpush
@endsection
