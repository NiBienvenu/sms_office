{{-- create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-book"></i> Create New Course</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('courses.store') }}" method="POST">
                @csrf
                @include('course._form')
            </form>
        </div>
    </div>
</div>
@endsection
