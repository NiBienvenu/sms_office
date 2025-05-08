{{-- index.blade.php --}}
@extends('layouts.app')

@section('title', 'Academic Years')

@section('content')
<div class="container-fluid">
    <div class="card shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-range"></i> Academic Years</h5>
            <a href="{{ route('academic-years.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> New Academic Year
            </a>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('academic-years.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Search by year..." value="{{ request('search') }}">
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

            <!-- Academic Years Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Year</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Current</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($academicYears as $year)
                        <tr>
                            <td class="fw-bold">{{ $year->year }}</td>
                            <td>
                                <small>
                                    <i class="bi bi-calendar-check"></i> {{ $year->start_date->format('M d, Y') }} -
                                    <i class="bi bi-calendar-x"></i> {{ $year->end_date->format('M d, Y') }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $year->status == 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($year->status) }}
                                </span>
                            </td>
                            <td>
                                @if($year->current)
                                    <span class="badge bg-warning">
                                        <i class="bi bi-star-fill"></i> Current
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('academic-years.show', $year) }}"
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('academic-years.edit', $year) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete('{{ $year->id }}')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $year->id }}"
                                      action="{{ route('academic-years.destroy', $year) }}"
                                      method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <i class="bi bi-calendar-x display-4 d-block"></i>
                                No academic years found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $academicYears->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function confirmDelete(yearId) {
        if (confirm('Are you sure you want to delete this academic year?')) {
            document.getElementById('delete-form-' + yearId).submit();
        }
    }
</script>
@endpush
@endsection
