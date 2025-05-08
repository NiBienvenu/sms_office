{{-- show.blade.php --}}
@extends('layouts.app')

@section('title', 'Department Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-building"></i> Department Details</h5>
            <div class="btn-group">
                <a href="{{ route('departments.index') }}" class="btn p-2 btn-warning  btn-sm">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <a href="{{ route('departments.edit', $department) }}" class="btn p-2 btn-light btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <button type="button" class="btn p-2 btn-danger btn-sm" onclick="confirmDelete('{{ $department->id }}')">
                    <i class="bi bi-trash"></i> Delete
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-6">
                    <h6>Basic Information</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Name</th>
                            <td>{{ $department->name }}</td>
                        </tr>
                        <tr>
                            <th>Code</th>
                            <td>{{ $department->code }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <span class="badge bg-{{ $department->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($department->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Department Details</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Head Teacher</th>
                            <td>{{ $department->headTeacher->full_name ?? 'Not Assigned' }}</td>
                        </tr>
                        <tr>
                            <th>Academic Year</th>
                            <td>{{ $department->academicYear->name }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ \Carbon\Carbon::createFromTimestamp($department->created_at)->format('M d, Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                @if($department->description)
                <div class="col-12">
                    <h6>Description</h6>
                    <p class="card-text">{{ $department->description }}</p>
                </div>
                @endif
            </div>

            <form id="delete-form-{{ $department->id }}"
                  action="{{ route('departments.destroy', $department) }}"
                  method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
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
