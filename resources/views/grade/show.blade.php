@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-journal-text"></i> Détails de la note</h5>
            <div>
                <a href="{{ route('grades.edit', $grade) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
                <a href="{{ route('grades.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Information sur l'élève</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 d-flex">
                                <div class="flex-shrink-0">
                                    <img src="{{ $grade->student->photo ? asset('storage/' . $grade->student->photo) : asset('images/default-avatar.png') }}"
                                         class="rounded-circle" width="80" height="80"
                                         alt="Photo de {{ $grade->student->first_name }}">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h5>{{ $grade->student->last_name }} {{ $grade->student->first_name }}</h5>
                                    <p class="text-muted mb-1">ID: {{ $grade->student->student_id }}</p>
                                    <p class="text-muted mb-0">Classe: {{ $grade->student->class->name ?? 'Non assigné' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Détails académiques</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Cours:</strong></p>
                                    <p class="badge bg-info">{{ $grade->course->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Enseignant:</strong></p>
                                    <p>{{ $grade->teacher->last_name }} {{ $grade->teacher->first_name }}</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Année académique:</strong></p>
                                    <p>{{ $grade->student->academicYear->year}}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Trimestre:</strong></p>
                                    <p>Trimestre {{ $grade->trimester }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Détails de la note</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="display-4 fw-bold">
                                    {{ $grade->score }} / {{ $grade->max_score }}
                                </div>
                                <div class="progress mt-3" style="height: 20px;">
                                    <div class="progress-bar {{ $grade->percentage >= 70 ? 'bg-success' : ($grade->percentage >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                         role="progressbar"
                                         style="width: {{ $grade->percentage }}%;"
                                         aria-valuenow="{{ $grade->percentage }}"
                                         aria-valuemin="0"
                                         aria-valuemax="100">
                                        {{ number_format($grade->percentage, 2) }}%
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-3">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">Informations supplémentaires</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Type de note:</strong></p>
                                            <p>{{ $grade->grade_type }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Date d'enregistrement:</strong></p>
                                            <p>{{ $grade->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($grade->comment)
                            <div class="card">
                                <div class="card-header bg-light py-2">
                                    <h6 class="mb-0">Commentaire</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $grade->comment }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
