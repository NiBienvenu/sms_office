@extends('layouts.app')

@section('title', 'Créer une Nouvelle Classe')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-plus-circle"></i> Création d'une Nouvelle Classe
            </h5>
            <a href="{{ route('class-rooms.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Retour à la Liste
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('class-rooms.store') }}" method="POST">
                @csrf

                @include('classRoom._form', ['classRoom' => new App\Models\ClassRoom()])

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                    <button type="reset" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Réinitialiser
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
