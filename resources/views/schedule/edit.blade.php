@extends('layouts.app')

@section('title', 'Edit Schedule')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Edit Schedule</h5>
            <a href="{{ route('schedules.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('schedules.update', $schedule) }}" method="POST">
                @csrf
                @method('PUT')
                @include('schedule._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Schedule
                    </button>
                    <a href="{{ route('schedules.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
