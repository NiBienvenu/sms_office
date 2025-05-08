@extends('layouts.app')

@section('title', 'Course Details')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-book"></i> Course Details</h4>
                <div>
                    <a href="{{ route('courses.edit', $course) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Basic Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th class="w-25">Code:</th>
                            <td>{{ $course->code }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $course->name }}</td>
                        </tr>
                        <tr>
                            <th>Subject:</th>
                            <td>{{ $course->subject->name }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $course->department->name }}</td>
                        </tr>
                        <tr>
                            <th>Academic Year:</th>
                            <td>{{ $course->academicYear->name }}</td>
                        </tr>
                        <tr>
                            <th>Credits:</th>
                            <td>{{ $course->credits }}</td>
                        </tr>
                        <tr>
                            <th>Hours/Week:</th>
                            <td>{{ $course->hours_per_week }}</td>
                        </tr>
                        <tr>
                            <th>Type:</th>
                            <td>
                                <span class="badge {{ $course->course_type == 'Mandatory' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $course->course_type }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge {{ $course->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Additional Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="fw-bold">Description</h6>
                        <p class="mb-0">{{ $course->description ?: 'No description available.' }}</p>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-bold">Objectives</h6>
                        <p class="mb-0">{{ $course->objectives ?: 'No objectives specified.' }}</p>
                    </div>
                    <div>
                        <h6 class="fw-bold">Assessment Method</h6>
                        <p class="mb-0">{{ $course->assessment_method ?: 'No assessment method specified.' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Teachers -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Assigned Teachers</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($course->teachers as $teacher)
                                    <tr>
                                        <td>{{ $teacher->name }}</td>
                                        <td>{{ $teacher->email }}</td>
                                        <td>{{ $teacher->department->name }}</td>
                                        <td>
                                            <span class="badge {{ $teacher->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($teacher->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('teachers.show', $teacher) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="bi bi-person-x display-4 d-block"></i>
                                            No teachers assigned to this course
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
