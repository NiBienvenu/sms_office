
@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-credit-card"></i>
                Payment #{{ $payment->reference_number }}
            </h5>
            <div>
                <a href="{{ route('payments.edit', $payment) }}" class="btn btn-light me-2">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('payments.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Payment Overview -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Payment Overview</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">Student</p>
                                    <p class="mb-3 fw-bold">{{ $payment->student->name }}</p>
                                    <p class="mb-1 text-muted">Registration No.</p>
                                    <p class="mb-0 fw-bold">{{ $payment->student->registration_number }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">Academic Year</p>
                                    <p class="mb-3 fw-bold">{{ $payment->academicYear->name }}</p>
                                    <p class="mb-1 text-muted">Semester</p>
                                    <p class="mb-0 fw-bold">{{ $payment->semester }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">Payment Date</p>
                                    <p class="mb-3 fw-bold">{{ $payment->payment_date->format('d/m/Y') }}</p>
                                    <p class="mb-1 text-muted">Payment Type</p>
                                    <p class="mb-0 fw-bold">{{ $payment->payment_type }}</p>
                                </div>
                                <div class="col-md-3">
                                    <p class="mb-1 text-muted">Status</p>
                                    <p class="mb-3">
                                        <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </p>
                                    <p class="mb-1 text-muted">Total Amount</p>
                                    <p class="mb-0 fw-bold">{{ number_format($payment->amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Payment Details</h6>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDetailModal">
                                <i class="bi bi-plus-circle"></i> Add Detail
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fee Type</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payment->paymentDetails as $detail)
                                        <tr>
                                            <td>{{ $detail->fee_type }}</td>
                                            <td>{{ number_format($detail->amount, 2) }}</td>
                                            <td>{{ $detail->description ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editDetailModal"
                                                        data-detail-id="{{ $detail->id }}"
                                                        data-fee-type="{{ $detail->fee_type }}"
                                                        data-amount="{{ $detail->amount }}"
                                                        data-description="{{ $detail->description }}">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="confirmDeleteDetail('{{ $detail->id }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                                <form id="delete-detail-{{ $detail->id }}"
                                                    action="{{ route('payment-details.destroy', $detail) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No payment details found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Total</th>
                                            <th>{{ number_format($payment->amount, 2) }}</th>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Detail Modal -->
@include('payments._add_detail_modal', ['payment' => $payment])

<!-- Edit Detail Modal -->
@include('payments._edit_detail_modal')

@push('scripts')
<script>
function confirmDeleteDetail(detailId) {
    if (confirm('Are you sure you want to delete this payment detail?')) {
        document.getElementById('delete-detail-' + detailId).submit();
    }
}

// Edit Detail Modal Handler
const editDetailModal = document.getElementById('editDetailModal');
if (editDetailModal) {
    editDetailModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const detailId = button.getAttribute('data-detail-id');
        const feeType = button.getAttribute('data-fee-type');
        const amount = button.getAttribute('data-amount');
        const description = button.getAttribute('data-description');

        const modal = this;
        modal.querySelector('form').action = '/payment-details/' + detailId;
        modal.querySelector('#edit_fee_type').value = feeType;
        modal.querySelector('#edit_amount').value = amount;
        modal.querySelector('#edit_description').value = description;
    });
}
</script>
@endpush
@endsection
