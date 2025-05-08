@extends('layouts.app')

@section('title', 'Détails de l\'étudiant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Informations principales -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="{{ $student->photo ? asset($student->photo) : asset('images/default-avatar.png') }}"
                         class="rounded-circle mb-3" width="150" height="150"
                         alt="Photo de {{ $student->first_name }}">
                    <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>
                    <p class="text-muted mb-1">Matricule: {{ $student->matricule }}</p>
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary me-2">{{ $student->classRoom->name ?? '' }}</span>
                        <span class="badge bg-info">{{ $student->education_level }}</span>
                    </div>
                    <div class="border-top pt-3">
                        <div class="row text-start">
                            <div class="col-6 mb-2">
                                <i class="bi bi-telephone text-primary"></i> {{ $student->phone }}
                            </div>
                            <div class="col-6 mb-2">
                                <i class="bi bi-envelope text-primary"></i> {{ $student->email }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i> Modifier
                    </a>
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="confirmDelete('{{ $student->id }}')">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>

        <!-- Détails complets -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personal">
                                <i class="bi bi-person"></i> Personnel
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#academic">
                                <i class="bi bi-mortarboard"></i> Académique
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#guardian">
                                <i class="bi bi-people"></i> Tuteur
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#health">
                                <i class="bi bi-heart-pulse"></i> Santé
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Informations personnelles -->
                        <div class="tab-pane fade show active" id="personal">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date de naissance</label>
                                    <p class="form-control-static">{{ $student->birth_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Lieu de naissance</label>
                                    <p class="form-control-static">{{ $student->birth_place }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Nationalité</label>
                                    <p class="form-control-static">{{ $student->nationality }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Genre</label>
                                    <p class="form-control-static">{{ $student->gender }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <p class="form-control-static">{{ $student->address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informations académiques -->
                        <div class="tab-pane fade" id="academic">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Date d'admission</label>
                                    <p class="form-control-static">{{ $student->admission_date->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Année académique</label>
                                    <p class="form-control-static">{{ $student->academicYear->year }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Niveau d'éducation</label>
                                    <p class="form-control-static">{{ $student->education_level }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Classe actuelle</label>
                                    <p class="form-control-static">{{ $student->classRoom->name ?? '' }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">École précédente</label>
                                    <p class="form-control-static">{{ $student->previous_school ?? 'Non renseigné' }}</p>
                                </div>

                                <!-- Cours inscrits -->
                                <div class="col-12 mt-4">
                                    <h6 class="border-bottom pb-2">Cours inscrits</h6>
                                    @if($student->courseEnrollments->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Cours</th>
                                                        <th>Professeur</th>
                                                        <th>Statut</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($student->courseEnrollments as $enrollment)
                                                    <tr>
                                                        <td>{{ $enrollment->course->name }}</td>
                                                        <td>{{ $enrollment->course->teacher->name }}</td>
                                                        <td>
                                                            <span class="badge bg-success">Inscrit</span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Aucun cours inscrit</p>
                                    @endif
                                </div>

                                <!-- Notes -->
                                <div class="col-12 mt-4">
                                    <h6 class="border-bottom pb-2">Notes récentes</h6>
                                    @if($student->grades->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Cours</th>
                                                        <th>Note</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($student->grades->take(5) as $grade)
                                                    <tr>
                                                        <td>{{ $grade->course->name }}</td>
                                                        <td>{{ $grade->score }}/20</td>
                                                        <td>{{ $grade->created_at->format('d/m/Y') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">Aucune note enregistrée</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Informations du tuteur -->
                        <div class="tab-pane fade" id="guardian">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom du tuteur</label>
                                    <p class="form-control-static">{{ $student->guardian_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Relation</label>
                                    <p class="form-control-static">{{ $student->guardian_relationship }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <p class="form-control-static">{{ $student->guardian_phone }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <p class="form-control-static">{{ $student->guardian_email ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Profession</label>
                                    <p class="form-control-static">{{ $student->guardian_occupation }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Adresse</label>
                                    <p class="form-control-static">{{ $student->guardian_address }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Informations de santé -->
                        <div class="tab-pane fade" id="health">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Groupe sanguin</label>
                                    <p class="form-control-static">{{ $student->blood_group ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact d'urgence</label>
                                    <p class="form-control-static">{{ $student->emergency_contact }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Problèmes de santé</label>
                                    <p class="form-control-static">
                                        @if($student->health_issues)
                                            {{ $student->health_issues }}
                                        @else
                                            <span class="text-muted">Aucun problème de santé signalé</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paiements récents -->
            <div class="card shadow-sm mt-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-cash"></i> Derniers paiements</h6>
                </div>
                <div class="card-body">
                    @if($student->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($student->payments->take(5) as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td>{{ number_format($payment->amount, 0, ',', ' ') }} F</td>
                                        <td>{{ $payment->type }}</td>
                                        <td>
                                            <span class="badge bg-{{ $payment->status === 'paid' ? 'success' : 'warning' }}">
                                                {{ $payment->status === 'paid' ? 'Payé' : 'En attente' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun paiement enregistré</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(studentId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')) {
        document.getElementById('delete-form-' + studentId).submit();
    }
}
</script>
@endpush
@endsection
