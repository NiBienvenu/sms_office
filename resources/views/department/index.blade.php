@extends('layouts.app')

@section('title', 'Department Management')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-building"></i> Department List</h5>
            <a href="{{ route('departments.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> New Department
            </a>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('departments.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="Search by name or code..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="academic_year" class="form-select">
                        <option value="">All Academic Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}"
                                {{ request('academic_year') == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Departments Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Head Teacher</th>
                            <th>Academic Year</th>
                            <th>Courses</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($departments as $department)
                        <tr>
                            <td>{{ $department->code }}</td>
                            <td>{{ $department->name }}</td>
                            <td>
                                {{ $department->head ? $department->head->fullname : 'N/A' }}
                            </td>
                            <td>{{ $department->academicYear->year }}</td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $department->courses_count }} Courses
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $department->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                    {{ ucfirst($department->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('departments.show', $department) }}"
                                       class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('departments.edit', $department) }}"
                                       class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $department->id }}')"
                                            title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $department->id }}"
                                      action="{{ route('departments.destroy', $department) }}"
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
                                No departments found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $departments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(departmentId) {
    if (confirm('Are you sure you want to delete this department?')) {
        document.getElementById('delete-form-' + departmentId).submit();
    }
}
</script>
@endpush
@endsection
