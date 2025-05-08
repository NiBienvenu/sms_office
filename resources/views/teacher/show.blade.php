@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-person-badge"></i> Teacher Details
            </h5>
            <div>
                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-light me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('teachers.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Teacher Photo and Basic Info -->
                <div class="col-md-3 text-center mb-4">
                    <img src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : asset('images/default-avatar.png') }}"
                         class="rounded-circle img-thumbnail mb-3"
                         style="width: 200px; height: 200px; object-fit: cover;"
                         alt="Photo of {{ $teacher->first_name }}">

                    <h5>{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
                    <p class="text-muted">{{ $teacher->position }}</p>

                    <div class="d-grid">
                        <span class="badge bg-primary mb-2">{{ $teacher->department->name }}</span>
                        <span class="badge bg-info">{{ $teacher->contract_type }}</span>
                    </div>
                </div>

                <!-- Detailed Information -->
                <div class="col-md-9">
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Personal Information</h6>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Employee ID:</strong></p>
                            <p>{{ $teacher->employee_id }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Email:</strong></p>
                            <p>{{ $teacher->email }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Phone:</strong></p>
                            <p>{{ $teacher->phone }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Gender:</strong></p>
                            <p>{{ ucfirst($teacher->gender) }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Birth Date:</strong></p>
                            <p>{{ $teacher->birth_date->format('M d, Y') }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Nationality:</strong></p>
                            <p>{{ $teacher->nationality }}</p>
                        </div>

                        <div class="col-12">
                            <p class="mb-1"><strong>Address:</strong></p>
                            <p>{{ $teacher->address }}</p>
                        </div>

                        <!-- Professional Information -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Professional Information</h6>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Department:</strong></p>
                            <p>{{ $teacher->department->name }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Position:</strong></p>
                            <p>{{ $teacher->position }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Joining Date:</strong></p>
                            <p>{{ $teacher->joining_date->format('M d, Y') }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Qualification:</strong></p>
                            <p>{{ $teacher->qualification }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Specialization:</strong></p>
                            <p>{{ $teacher->specialization }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Experience:</strong></p>
                            <p>{{ $teacher->experience_years }} years</p>
                        </div>

                        <!-- Emergency Contact -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Emergency Contact</h6>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1"><strong>Contact Name:</strong></p>
                            <p>{{ $teacher->emergency_contact_name }}</p>
                        </div>

                        <div class="col-md-6">
                            <p class="mb-1"><strong>Contact Phone:</strong></p>
                            <p>{{ $teacher->emergency_contact_phone }}</p>
                        </div>

                        <!-- Additional Information -->
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Employment Details</h6>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Contract Type:</strong></p>
                            <p>{{ ucfirst($teacher->contract_type) }}</p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Employment Status:</strong></p>
                            <p>
                                @if($teacher->employment_status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($teacher->employment_status == 'inactive')
                                    <span class="badge bg-danger">Inactive</span>
                                @else
                                    <span class="badge bg-warning">On Leave</span>
                                @endif
                            </p>
                        </div>

                        <div class="col-md-4">
                            <p class="mb-1"><strong>Salary Grade:</strong></p>
                            <p>{{ $teacher->salary_grade }}</p>
                        </div>

                        @if($teacher->previous_employment)
                        <div class="col-12">
                            <p class="mb-1"><strong>Previous Employment:</strong></p>
                            <p>{{ $teacher->previous_employment }}</p>
                        </div>
                        @endif

                        @if($teacher->additional_info)
                        <div class="col-12">
                            <h6 class="text-primary border-bottom pb-2">Additional Information</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <pre class="mb-0">{{ json_encode($teacher->additional_info, JSON_PRETTY_PRINT) }}</pre>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
