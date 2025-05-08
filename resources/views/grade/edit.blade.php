@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-pencil"></i> Modifier la note</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('grades.update', $grade) }}" method="POST">
                @csrf
                @method('PUT')

                @include('grade._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Mettre à jour
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
