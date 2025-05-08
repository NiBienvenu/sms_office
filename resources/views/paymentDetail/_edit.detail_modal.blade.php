
<div class="modal fade" id="editDetailModal" tabindex="-1" aria-labelledby="editDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editDetailModalLabel">Edit Payment Detail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_fee_type" class="form-label">Fee Type <span class="text-danger">*</span></label>
                        <select name="fee_type" id="edit_fee_type" class="form-select" required>
                            <option value="">Select Fee Type</option>
                            @foreach(['Tuition', 'Registration', 'Library', 'Laboratory', 'Accommodation', 'Other'] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <input type="text" name="description" id="edit_description" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button
