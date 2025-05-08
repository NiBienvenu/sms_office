
<div class="payment-detail-row row g-3 mb-3">
    <input type="hidden" name="payment_details[{{ $index }}][id]" value="{{ $detail->id ?? '' }}">

    <div class="col-md-4">
        <label for="fee_type_{{ $index }}" class="form-label">Fee Type <span class="text-danger">*</span></label>
        <select name="payment_details[{{ $index }}][fee_type]" id="fee_type_{{ $index }}"
            class="form-select @error('payment_details.' . $index . '.fee_type') is-invalid @enderror" required>
            <option value="">Select Fee Type</option>
            @foreach(['Tuition', 'Registration', 'Library', 'Laboratory', 'Accommodation', 'Other'] as $type)
                <option value="{{ $type }}"
                    {{ old('payment_details.' . $index . '.fee_type', $detail->fee_type ?? '') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>
        @error('payment_details.' . $index . '.fee_type')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-3">
        <label for="amount_{{ $index }}" class="form-label">Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="payment_details[{{ $index }}][amount]" id="amount_{{ $index }}"
            class="form-control @error('payment_details.' . $index . '.amount') is-invalid @enderror"
            value="{{ old('payment_details.' . $index . '.amount', $detail->amount ?? '') }}"
            required onchange="updateTotalAmount()">
        @error('payment_details.' . $index . '.amount')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="description_{{ $index }}" class="form-label">Description</label>
        <input type="text" name="payment_details[{{ $index }}][description]" id="description_{{ $index }}"
            class="form-control @error('payment_details.' . $index . '.description') is-invalid @enderror"
            value="{{ old('payment_details.' . $index . '.description', $detail->description ?? '') }}">
        @error('payment_details.' . $index . '.description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-1 d-flex align-items-end">
        <button type="button" class="btn btn-danger btn-sm" onclick="removePaymentDetail(this)">
            <i class="bi bi-trash"></i>
        </button>
    </div>
</div>
