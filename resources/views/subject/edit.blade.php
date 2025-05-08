@extends('layouts.app')

@section('title', 'Edit Subject')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-warning d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-pencil"></i> Edit Subject</h5>
                    <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('subjects.update', $subject) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('subject._form')

                        <div class="text-end mt-4">
                            <a href="{{ route('subjects.show', $subject) }}" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle"></i> Update Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
