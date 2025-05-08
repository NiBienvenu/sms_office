@extends('layouts.app')

@section('title', 'Student Management')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-people"></i> Student List</h5>
            <a href="{{ route('students.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> New Student
            </a>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('students.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <!-- Student Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Student ID</th>
                            <th>Full Name</th>
                            <th>Class</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                <img src="{{ $student->photo ? asset($student->photo) : asset('images/default-avatar.png') }}"
                                     class="rounded-circle" width="40" height="40"
                                     alt="Photo of {{ $student->first_name }}">
                            </td>
                            <td>{{ $student->matricule }}</td>
                            <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                            <td>
                                <span class="badge bg-info">{{ $student->classRoom->name ?? '' }}</span>
                            </td>
                            <td>
                                <small>
                                    <i class="bi bi-telephone"></i> {{ $student->phone }}<br>
                                    <i class="bi bi-envelope"></i> {{ $student->email }}
                                </small>
                            </td>
                            <td>
                                @if($student->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('students.show', $student) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('students.edit', $student) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $student->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $student->id }}"
                                      action="{{ route('students.destroy', $student) }}"
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox display-4 d-block"></i>
                                No students found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $students->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
    function confirmDelete(studentId) {
        if (confirm('Are you sure you want to delete this student?')) {
            document.getElementById('delete-form-' + studentId).submit();
        }
    }
</script>
@endpush
@endsection
