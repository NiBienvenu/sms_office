@extends('layouts.print')

@section('title', 'Impression du bulletin')

@section('content')
<div class="print-container">
    <!-- En-tête de l'école -->
    <div class="school-header text-center mb-4">
        <h4 class="mb-0">ÉTABLISSEMENT SCOLAIRE</h4>
        <p class="small">Adresse de l'école - Téléphone - Email</p>
        <h2 class="my-3">BULLETIN DE NOTES</h2>
        <p class="text-center mb-0">Année scolaire: {{ $bulletin->academicYear->year }}</p>
        <p class="text-center">Trimestre: {{ $bulletin->trimester }}</p>
    </div>

    <!-- Informations de l'élève -->
    <div class="student-info mb-4">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Nom et prénom:</th>
                        <td>{{ $bulletin->student->last_name }} {{ $bulletin->student->first_name }}</td>
                    </tr>
                    <tr>
                        <th>Matricule:</th>
                        <td>{{ $bulletin->student->student_id }}</td>
                    </tr>
                    <tr>
                        <th>Date de naissance:</th>
                        <td>{{ $bulletin->student->birth_date ? $bulletin->student->birth_date->format('d/m/Y') : 'Non renseigné' }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <th>Classe:</th>
                        <td>{{ $bulletin->classRoom->name }}</td>
                    </tr>
                    <tr>
                        <th>Effectif:</th>
                        <td>{{ $classStats['totalStudents'] }} élèves</td>
                    </tr>
                    <tr>
                        <th>Professeur principal:</th>
                        <td>{{ $bulletin->classRoom->teacher->last_name ?? 'Non assigné' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Tableau des notes -->
    <div class="grades-table">
        <table class="table table-bordered table-sm">
            <thead class="table-light">
                <tr>
                    <th rowspan="2" class="align-middle">MATIÈRES</th>
                    <th rowspan="2" class="align-middle text-center">COEF</th>
                    <th colspan="3" class="text-center">NOTES</th>
                    <th rowspan="2" class="align-middle text-center">MOYENNE</th>
                    <th colspan="3" class="text-center">CLASSE</th>
                </tr>
                <tr>
                    <th class="text-center">Devoirs</th>
                    <th class="text-center">Examen</th>
                    <th class="text-center">Moyenne</th>
                    <th class="text-center">Min</th>
                    <th class="text-center">Max</th>
                    <th class="text-center">Moy</th>
                </tr>
            </thead>
            <tbody>
                @php $totalCoefficient = 0; $totalPoints = 0; @endphp

                @forelse($courseAverages as $courseId => $data)
                    @php
                        $totalCoefficient += $data['course']->coefficient;
                        $totalPoints += ($data['average'] * $data['course']->coefficient);
                    @endphp
                    <tr>
                        <td>{{ $data['course']->name }}</td>
                        <td class="text-center">{{ $data['course']->coefficient }}</td>
                        <td class="text-center">
                            @php
                                $devoirs = $data['grades']->where('grade_type', 'Devoir');
                                if($devoirs->count() > 0) {
                                    echo round($devoirs->avg('percentage'), 2) . '/20';
                                } else {
                                    echo '-';
                                }
                            @endphp
                        </td>
                        <td class="text-center">
                            @php
                                $examens = $data['grades']->where('grade_type', 'Examen');
                                if($examens->count() > 0) {
                                    echo round($examens->avg('percentage'), 2) . '/20';
                                } else {
                                    echo '-';
                                }
                            @endphp
                        </td>
                        <td class="text-center">{{ $data['average'] }}/20</td>
                        <td class="text-center fw-bold {{ $data['average'] >= 10 ? 'text-success' : 'text-danger' }}">
                            {{ $data['average'] }}/20
                        </td>
                        <td class="text-center">{{ $data['class_min'] }}/20</td>
                        <td class="text-center">{{ $data['class_max'] }}/20</td>
                        <td class="text-center">{{ $data['class_avg'] }}/20</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">Aucune note disponible</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="5" class="text-end">MOYENNE GÉNÉRALE:</th>
                    <th class="text-center fw-bold {{ $bulletin->average >= 10 ? 'text-success' : 'text-danger' }}">
                        {{ $bulletin->average ?? number_format($totalCoefficient > 0 ? $totalPoints / $totalCoefficient : 0, 2) }}/20
                    </th>
                    <th colspan="3" class="text-center">
                        Rang: {{ $bulletin->rank ?? '-' }}<sup>e</sup>/{{ $classStats['totalStudents'] }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Statistiques de la classe -->
    <div class="class-stats mb-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Statistiques de la classe</h6>
                    </div>
                    <div class="card-body p-2">
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <th>Moyenne de la classe:</th>
                                <td>{{ $classStats['classAverage'] }}/20</td>
                            </tr>
                            <tr>
                                <th>Meilleure moyenne:</th>
                                <td>{{ $classStats['highestAverage'] }}/20</td>
                            </tr>
                            <tr>
                                <th>Moyenne la plus basse:</th>
                                <td>{{ $classStats['lowestAverage'] }}/20</td>
                            </tr>
                            <tr>
                                <th>Taux de réussite:</th>
                                <td>{{ $classStats['passRate'] }}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Appréciations</h6>
                    </div>
                    <div class="card-body p-2">
                        <div class="mb-2">
                            <small class="text-muted">Professeur principal:</small>
                            <p class="mb-0">{{ $bulletin->teacher_comments ?? 'Aucun commentaire' }}</p>
                        </div>
                        <div>
                            <small class="text-muted">Direction:</small>
                            <p class="mb-0">{{ $bulletin->principal_comments ?? 'Aucun commentaire' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Décision et signatures -->
    <div class="decision-section mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0">Décision</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="mb-0 fw-bold">
                        @if($bulletin->average >= 10)
                            L'élève est admis(e) en classe supérieure.
                        @else
                            Résultats insuffisants. Un effort supplémentaire est nécessaire.
                        @endif
                    </p>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 text-center">
                        <p class="mb-0">Les Parents</p>
                        <div class="signature-line"></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="mb-0">Le Professeur Principal</p>
                        <div class="signature-line"></div>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="mb-0">Le Chef d'Établissement</p>
                        <div class="signature-line"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Date et cachet -->
    <div class="print-footer text-end">
        <p>Fait à __________________, le {{ now()->format('d/m/Y') }}</p>
        <div class="stamp">Cachet de l'école</div>
    </div>
</div>

<style>
    @media print {
        body {
            font-size: 12px;
        }
        .print-container {
            width: 100%;
            max-width: 100%;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 40px;
        }
        .stamp {
            border: 1px dashed #000;
            padding: 20px;
            width: 150px;
            height: 80px;
            text-align: center;
            margin-left: auto;
            margin-top: 10px;
        }
        .table {
            font-size: 11px;
        }
        .card {
            border: 1px solid #ddd;
        }
        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #ddd;
        }
    }
</style>
@endsection
