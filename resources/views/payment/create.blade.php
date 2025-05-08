

{{-- create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Academic Year')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-calendar-plus"></i> Create Payment </h5>
        </div>

        <div class="card-body">
            <form action="{{ route('payments.store') }}" method="POST">
                @csrf
                @include('payment._form')

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Create Payment
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
