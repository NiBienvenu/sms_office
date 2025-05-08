@extends('layouts.app')

@section('title', 'Payment Management')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-credit-card"></i> Payment List</h5>
            <a href="{{ route('payments.create') }}" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> New Payment
            </a>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form action="{{ route('payments.index') }}" method="GET" class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="matricule" class="form-control" placeholder="Search by Matricule..." value="{{ request('matricule') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="academic_year" class="form-select">
                        <option value="">All Academic Years</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ request('academic_year') == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="semester" class="form-select">
                        <option value="">All Semesters</option>
                        <option value="First" {{ request('semester') == 'First' ? 'selected' : '' }}>First</option>
                        <option value="Second" {{ request('semester') == 'Second' ? 'selected' : '' }}>Second</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-filter"></i> Filter
                    </button>
                </div>
            </form>

            <!-- Payments Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Student</th>
                            <th>Academic Year</th>
                            <th>Semester</th>
                            <th>Amount</th>
                            <th>Payment Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->reference_number }}</td>
                            <td>{{ $payment->student->name }}</td>
                            <td>{{ $payment->academicYear->name }}</td>
                            <td>{{ $payment->semester }}</td>
                            <td>${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_type }}</td>
                            <td>
                                <span class="badge {{ $payment->status == 'completed' ? 'bg-success' : ($payment->status == 'pending' ? 'bg-warning' : 'bg-danger') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('payments.edit', $payment) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('{{ $payment->id }}')" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <form id="delete-form-{{ $payment->id }}" action="{{ route('payments.destroy', $payment) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox display-4 d-block"></i>
                                No payments found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end mt-3">
                {{ $payments->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(paymentId) {
    if (confirm('Are you sure you want to delete this payment?')) {
        document.getElementById('delete-form-' + paymentId).submit();
    }
}
</script>
@endpush
@endsection
