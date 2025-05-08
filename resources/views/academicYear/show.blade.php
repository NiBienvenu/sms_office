{{-- show.blade.php --}}
@extends('layouts.app')

@section('title', 'Academic Year Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-calendar-week"></i>
                Academic Year: {{ $academicYear->year }}
            </h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="border-bottom pb-2">Basic Information</h6>
                    <dl class="row">
                        <dt class="col-sm-4">Year</dt>
                        <dd class="col-sm-8">{{ $academicYear->year }}</dd>

                        <dt class="col-sm-4">Start Date</dt>
                        <dd class="col-sm-8">{{ $academicYear->start_date->format('M d, Y') }}</dd>

                        <dt class="col-sm-4">End Date</dt>
                        <dd class="col-sm-8">{{ $academicYear->end_date->format('M d, Y') }}</dd>

                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $academicYear->status == 'active' ? 'success' : 'danger' }}">
                                {{ ucfirst($academicYear->status) }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Current</dt>
                        <dd class="col-sm-8">
                            @if($academicYear->current)
                                <span class="badge bg-warning">
                                    <i class="bi bi-star-fill"></i> Current Academic Year
                                </span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </dd>
                    </dl>
                </div>

                <div class="col-md-6">
                    <h6 class="border-bottom pb-2">Statistics</h6>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-people"></i> Students
                                    </h6>
                                    <h3 class="card-text">{{ $academicYear->students->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-book"></i> Courses
                                    </h6>
                                    <h3 class="card-text">{{ $academicYear->courses->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('academic-years.edit', $academicYear) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('academic-years.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
