@extends('layouts.app')

@section('title', 'Course Management')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-book"></i> Course List</h5>
            <a href="{{ route('courses.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> New Course
            </a>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('courses.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name or code..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="department" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ request('department') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="semester" class="form-select">
                        <option value="">All Semesters</option>
                        <option value="Fall" {{ request('semester') == 'Fall' ? 'selected' : '' }}>Fall</option>
                        <option value="Spring" {{ request('semester') == 'Spring' ? 'selected' : '' }}>Spring</option>
                        <option value="Summer" {{ request('semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="course_type" class="form-select">
                        <option value="">All Course Types</option>
                        <option value="Mandatory" {{ request('course_type') == 'Mandatory' ? 'selected' : '' }}>Mandatory</option>
                        <option value="Elective" {{ request('course_type') == 'Elective' ? 'selected' : '' }}>Elective</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Courses Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Semester</th>
                            <th>Credits</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->code }}</td>
                            <td>{{ $course->name }}</td>
                            <td>{{ $course->department->name }}</td>
                            <td>
                                <span class="badge bg-info">{{ $course->semester }}</span>
                            </td>
                            <td>{{ $course->credits }}</td>
                            <td>
                                <span class="badge {{ $course->course_type == 'Mandatory' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $course->course_type }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $course->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('courses.show', $course) }}"
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('courses.edit', $course) }}"
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $course->id }}')"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $course->id }}"
                                      action="{{ route('courses.destroy', $course) }}"
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 d-block"></i>
                                No courses found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $courses->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(courseId) {
    if (confirm('Are you sure you want to delete this course?')) {
        document.getElementById('delete-form-' + courseId).submit();
    }
}
</script>
@endpush
@endsection
