@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Barre d'actions -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('bulletins.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Retour à la liste
            </a>
        </div>
        <div class="btn-group">
            <a href="{{ route('bulletins.edit', $bulletin) }}" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Modifier
            </a>
            <a href="{{ route('bulletins.pdf', $bulletin) }}" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-pdf"></i> PDF
            </a>
            <a href="{{ route('bulletins.print', $bulletin) }}" class="btn btn-dark" target="_blank">
                <i class="bi bi-printer"></i> Imprimer
            </a>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#publishModal">
                <i class="bi bi-check-circle"></i> Publier
            </button>
        </div>
    </div>

    <!-- Informations du bulletin -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-journal-text"></i>
                Bulletin de {{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }} -
                Trimestre {{ $bulletin->trimester }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Informations générales</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="30%">Élève</th>
                                    <td>{{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</td>
                                </tr>
                                <tr>
                                    <th>Classe</th>
                                    <td>{{ $bulletin->classRoom->name }}</td>
                                </tr>
                                <tr>
                                    <th>Année académique</th>
                                    <td>{{ $bulletin->academicYear->year }}</td>
                                </tr>
                                <tr>
                                    <th>Trimestre</th>
                                    <td>Trimestre {{ $bulletin->trimester }}</td>
                                </tr>
                                <tr>
                                    <th>Professeur principal</th>
                                    <td>{{ $bulletin->classRoom->teacher->last_name ?? '' }} {{ $bulletin->classRoom->teacher->first_name ?? '' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Résultats</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center mb-3">
                                        <div class="display-4 fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                                            {{ $bulletin->average ?? '-' }}
                                        </div>
                                        <div class="text-muted">Moyenne générale</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-center mb-3">
                                        <div class="display-4 fw-bold {{ $bulletin->rank <= 3 ? 'text-success' : '' }}">
                                            {{ $bulletin->rank ?? '-' }}<sup>e</sup>
                                        </div>
                                        <div class="text-muted">Rang</div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-between">
                                        <div class="text-center">
                                            <div class="h5 fw-bold text-primary">{{ $classAverage ?? '-' }}</div>
                                            <div class="small text-muted">Moyenne de la classe</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="h5 fw-bold text-primary">{{ $totalStudents ?? '-' }}</div>
                                            <div class="small text-muted">Effectif</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="h5 fw-bold text-primary">{{ $status ?? '-' }}</div>
                                            <div class="small text-muted">Statut</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des notes -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Résultats par matière</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Matière</th>
                                    <th>Enseignant</th>
                                    <th>Coef.</th>
                                    <th>Notes</th>
                                    <th>Moyenne</th>
                                    <th>Moyenne x Coef.</th>
                                    <th>Rang</th>
                                    <th>Appréciation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($grades as $courseId => $courseGrades)
                                    @php
                                        $course = $courses->firstWhere('id', $courseId);
                                        $courseAverage = $courseAverages[$courseId] ?? '-';
                                        $coefficient = $course->coefficient ?? 1;
                                        $weightedAverage = $courseAverage != '-' ? $courseAverage * $coefficient : '-';
                                        $courseRank = $courseRanks[$courseId] ?? '-';
                                        $commentExists = isset($courseComments[$courseId]);
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $course->name }}</strong>
                                            @if($course->is_core)
                                                <span class="badge bg-primary ms-1">Principale</span>
                                            @endif
                                        </td>
                                        <td>{{ $course->teacher->last_name ?? '' }} {{ $course->teacher->first_name ?? '' }}</td>
                                        <td class="text-center">{{ $coefficient }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach($courseGrades as $grade)
                                                    <span class="badge {{ $grade->percentage >= 50 ? 'bg-success' : 'bg-danger' }}"
                                                          title="{{ $grade->grade_type }} - {{ $grade->evaluation_date->format('d/m/Y') }}">
                                                        {{ $grade->score }}/{{ $grade->max_score }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <strong class="{{ $courseAverage >= 10 ? 'text-success' : 'text-danger' }}">
                                                {{ $courseAverage != '-' ? number_format($courseAverage, 2) : '-' }}
                                            </strong>
                                        </td>
                                        <td class="text-center">
                                            {{ $weightedAverage != '-' ? number_format($weightedAverage, 2) : '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $courseRank }}<sup>{{ $courseRank == 1 ? 'er' : 'e' }}</sup>
                                        </td>
                                        <td>
                                            @if($commentExists)
                                                <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ $courseComments[$courseId] }}">
                                                    <i class="bi bi-chat-quote"></i>
                                                </button>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-3 text-muted">
                                            Aucune note disponible pour ce trimestre
                                        </td>
                                    </tr>
                                @endforelse
                                <tr class="table-primary">
                                    <td colspan="4" class="text-end fw-bold">Moyenne Générale :</td>
                                    <td class="text-center fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                                        {{ $bulletin->average ?? '-' }}
                                    </td>
                                    <td class="text-center fw-bold">
                                        {{ $weightedTotal ?? '-' }}
                                    </td>
                                    <td colspan="2" class="text-center">
                                        <span class="badge {{ $bulletin->rank <= 3 ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $bulletin->rank ?? '-' }}<sup>{{ $bulletin->rank == 1 ? 'er' : 'e' }}</sup> sur {{ $totalStudents ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Commentaires -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Commentaires du professeur principal</h6>
                        </div>
                        <div class="card-body">
                            @if($bulletin->teacher_comments)
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-quote"></i> {{ $bulletin->teacher_comments }}
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-chat-square"></i> Aucun commentaire disponible
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Commentaires du directeur</h6>
                        </div>
                        <div class="card-body">
                            @if($bulletin->principal_comments)
                                <div class="p-3 bg-light rounded">
                                    <i class="bi bi-quote"></i> {{ $bulletin->principal_comments }}
                                </div>
                            @else
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-chat-square"></i> Aucun commentaire disponible
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assiduité et Comportement -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Assiduité</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="display-6">{{ $absences ?? 0 }}</div>
                                    <div class="small text-muted">Absences</div>
                                </div>
                                <div class="col-4">
                                    <div class="display-6">{{ $latenesses ?? 0 }}</div>
                                    <div class="small text-muted">Retards</div>
                                </div>
                                <div class="col-4">
                                    <div class="display-6">{{ $excused ?? 0 }}</div>
                                    <div class="small text-muted">Justifiées</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Comportement</h6>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="display-6 {{ $sanctions ?? 0 > 0 ? 'text-danger' : 'text-success' }}">{{ $sanctions ?? 0 }}</div>
                                    <div class="small text-muted">Sanctions</div>
                                </div>
                                <div class="col-4">
                                    <div class="display-6 text-warning">{{ $warnings ?? 0 }}</div>
                                    <div class="small text-muted">Avertissements</div>
                                </div>
                                <div class="col-4">
                                    <div class="display-6 text-success">{{ $encouragements ?? 0 }}</div>
                                    <div class="small text-muted">Encouragements</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique d'évolution -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Évolution des résultats</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="height: 300px;">
                        <canvas id="evolutionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between">
                <div>

                    <small class="text-muted">
                        <i class="bi bi-calendar-event"></i> Généré le: {{ $bulletin->generated_at ? $bulletin->generated_at->format('d/m/Y H:i') : '-' }}
                    </small>
                </div>
                <div>
                    <small class="text-muted">
                        <i class="bi bi-tag"></i> Status:
                        @if($bulletin->status == 'draft')
                            <span class="badge bg-secondary">Brouillon</span>
                        @elseif($bulletin->status == 'pending')
                            <span class="badge bg-warning text-dark">En attente</span>
                        @elseif($bulletin->status == 'published')
                            <span class="badge bg-success">Publié</span>
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de publication -->
<div class="modal fade" id="publishModal" tabindex="-1" aria-labelledby="publishModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('bulletins.publish', $bulletin) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="publishModalLabel">Publication du bulletin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Vous êtes sur le point de publier le bulletin de <strong>{{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</strong> pour le trimestre {{ $bulletin->trimester }}.</p>
                    <p>Une fois publié, le bulletin sera visible par l'élève et ses parents.</p>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyParents" name="notify_parents" value="1">
                        <label class="form-check-label" for="notifyParents">
                            Notifier les parents par email
                        </label>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="notifyStudent" name="notify_student" value="1">
                        <label class="form-check-label" for="notifyStudent">
                            Notifier l'élève par email
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle"></i> Publier le bulletin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activation des tooltips Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Graphique d'évolution
        const ctx = document.getElementById('evolutionChart').getContext('2d');

        // Données d'exemple - à remplacer par les vraies données
        const trimesterData = {
            'Trimestre 1': {{ $averageHistory[1] ?? 'null' }},
            'Trimestre 2': {{ $averageHistory[2] ?? 'null' }},
            'Trimestre 3': {{ $averageHistory[3] ?? 'null' }}
        };

        const classAverageData = {
            'Trimestre 1': {{ $classAverageHistory[1] ?? 'null' }},
            'Trimestre 2': {{ $classAverageHistory[2] ?? 'null' }},
            'Trimestre 3': {{ $classAverageHistory[3] ?? 'null' }}
        };

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(trimesterData),
                datasets: [
                    {
                        label: 'Moyenne de l\'élève',
                        data: Object.values(trimesterData),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        tension: 0.1
                    },
                    {
                        label: 'Moyenne de la classe',
                        data: Object.values(classAverageData),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 20,
                        ticks: {
                            stepSize: 2
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw + '/20';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
