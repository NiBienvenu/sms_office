@extends('layouts.app')

@section('title', 'Create New User')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-person-plus"></i> Create New User</h5>
            <a href="{{ route('users.index') }}" class="btn btn-light">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('user._form')

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
