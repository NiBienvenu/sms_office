@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people"></i> Teacher Management</h5>
            <a href="{{ route('teachers.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> New Teacher
            </a>
        </div>

        <div class="card-body">
            <!-- Advanced Filters -->
            <form action="{{ route('teachers.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <select name="department_id" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="contract_type" class="form-select">
                        <option value="">All Contracts</option>
                        <option value="full-time" {{ request('contract_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                        <option value="part-time" {{ request('contract_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                        <option value="temporary" {{ request('contract_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <select name="employment_status" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('employment_status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('employment_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="on-leave" {{ request('employment_status') == 'on-leave' ? 'selected' : '' }}>On Leave</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                </div>

                <div class="col-md-1">
                    <a href="{{ route('teachers.index') }}" class="btn btn-secondary w-100">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>

            <!-- Teachers Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Position</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teachers as $teacher)
                        <tr>
                            <td>
                                <img src="{{ $teacher->photo ? asset('storage/' . $teacher->photo) : asset('images/default-avatar.png') }}"
                                     class="rounded-circle" width="40" height="40"
                                     alt="Photo of {{ $teacher->first_name }}">
                            </td>
                            <td>{{ $teacher->employee_id }}</td>
                            <td>
                                {{ $teacher->first_name }} {{ $teacher->last_name }}
                                <br>
                                <small class="text-muted">{{ $teacher->specialization }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $teacher->department->name }}</span>
                            </td>
                            <td>
                                <small>
                                    <i class="bi bi-telephone"></i> {{ $teacher->phone }}<br>
                                    <i class="bi bi-envelope"></i> {{ $teacher->email }}
                                </small>
                            </td>
                            <td>
                                @if($teacher->employment_status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($teacher->employment_status == 'inactive')
                                    <span class="badge bg-danger">Inactive</span>
                                @else
                                    <span class="badge bg-warning">On Leave</span>
                                @endif
                            </td>
                            <td>{{ $teacher->position }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('teachers.show', $teacher) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('teachers.edit', $teacher) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $teacher->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $teacher->id }}"
                                      action="{{ route('teachers.destroy', $teacher) }}"
                                      method="POST"
                                      class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 d-block"></i>
                                No teachers found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $teachers->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(teacherId) {
        if (confirm('Are you sure you want to delete this teacher?')) {
            document.getElementById('delete-form-' + teacherId).submit();
        }
    }
</script>
@endpush
@endsection
