@extends('layouts.app')

@section('title', 'Subject Details')

@section('content')
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4><i class="bi bi-book-half"></i> Subject Details</h4>
                <div>
                    <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <a href="{{ route('subjects.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Basic Information -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Basic Information</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th class="w-25">Code:</th>
                            <td>{{ $subject->code }}</td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td>{{ $subject->name }}</td>
                        </tr>
                        <tr>
                            <th>Department:</th>
                            <td>{{ $subject->department->name }}</td>
                        </tr>
                        <tr>
                            <th>Academic Year:</th>
                            <td>{{ $subject->academicYear->name }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge {{ $subject->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($subject->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Description</h6>
                </div>
                <div class="card-body">
                    {{ $subject->description ?: 'No description available.' }}
                </div>
            </div>
        </div>

        <!-- Associated Courses -->
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Associated Courses</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Credits</th>
                                    <th>Type</th>
                                    <th>Semester</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subject->courses as $course)
                                    <tr>
                                        <td>{{ $course->code }}</td>
                                        <td>{{ $course->name }}</td>
                                        <td>{{ $course->credits }}</td>
                                        <td>
                                            <span class="badge {{ $course->course_type == 'Mandatory' ? 'bg-primary' : 'bg-secondary' }}">
                                                {{ $course->course_type }}
                                            </span>
                                        </td>
                                        <td>{{ $course->semester }}</td>
                                        <td>
                                            <span class="badge {{ $course->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="bi bi-inbox display-4 d-block"></i>
                                            No courses associated with this subject
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
