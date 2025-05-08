@extends('layouts.app')

@section('title', 'Create Subject')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Create New Subject</h5>
                    <a href="{{ route('subjects.index') }}" class="btn btn-light">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('subjects.store') }}" method="POST">
                        @csrf
                        @include('subject._form')

                        <div class="text-end mt-4">
                            <button type="reset" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
