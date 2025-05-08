@extends('layouts.app')

@section('title', 'Create Schedule')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Create Schedule</h5>
            <a href="{{ route('schedules.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('schedules.store') }}" method="POST">
                @csrf
                @include('schedule._form')

                <!-- Submit Button -->
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        {{ isset($schedule) ? 'Update Schedule' : 'Create Schedule' }}
                    </button>
                    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
