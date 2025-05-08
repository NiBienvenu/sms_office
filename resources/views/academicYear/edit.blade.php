
{{-- edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Academic Year')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Edit Academic Year</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('academic-years.update', $academicYear) }}" method="POST">
                @csrf
                @method('PUT')
                @include('academicYear._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Academic Year
                    </button>
                    <a href="{{ route('academic-years.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
