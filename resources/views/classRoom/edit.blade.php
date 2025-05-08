@extends('layouts.app')

@section('title', 'Modifier la Classe')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-pencil"></i> Modification de Classe
            </h5>
            <div class="btn-group">
                <a href="{{ route('class-rooms.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Retour à la Liste
                </a>
                <a href="{{ route('class-rooms.show', $classRoom) }}" class="btn btn-light">
                    <i class="bi bi-eye"></i> Détails
                </a>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ route('class-rooms.update', $classRoom) }}" method="POST">
                @csrf
                @method('PUT')

                @include('classRoom._form', ['classRoom' => $classRoom])

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Mettre à Jour
                    </button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </div>
            </form>

            <form id="delete-form"
                  action="{{ route('class-rooms.destroy', $classRoom) }}"
                  method="POST"
                  class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>

    @if($classRoom->students_count > 0)
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="bi bi-people"></i> Liste des Étudiants
                ({{ $classRoom->students_count }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Date de Naissance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($classRoom->students as $student)
                        <tr>
                            <td>{{ $student->matricule }}</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('students.show', $student) }}"
                                   class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
function confirmDelete() {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette classe ? Cette action est irréversible.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endpush
@endsection
