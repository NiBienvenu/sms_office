{{-- create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Department')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-building"></i> Create New Department</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('departments.store') }}" method="POST">
                @csrf
                @include('department._form')
            </form>
        </div>
    </div>
</div>
@endsection
