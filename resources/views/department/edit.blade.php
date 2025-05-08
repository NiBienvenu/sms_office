{{-- edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-building"></i> Edit Department</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('departments.update', $department) }}" method="POST">
                @csrf
                @method('PUT')
                @include('department._form')
            </form>
        </div>
    </div>
</div>
@endsection
