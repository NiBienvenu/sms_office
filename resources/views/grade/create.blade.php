@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Ajouter une nouvelle note</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('grades.store') }}" method="POST">
                @csrf

                @include('grade._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                    <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
