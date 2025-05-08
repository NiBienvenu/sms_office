<div class="row g-4">
    <!-- Student and Academic Year Selection -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Student Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="student_search" class="form-label">Search Student (by Matricule) <span class="text-danger">*</span></label>
                        <input type="text" id="student_search" class="form-control" placeholder="Enter Matricule">
                        <input type="hidden" name="student_id" id="student_id" value="{{ old('student_id', $payment->student_id ?? '') }}">

                        <div id="student_search_result" class="list-group mt-2" style="display: none;"></div>

                        @error('student_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-3">
                        <label for="academic_year_id" class="form-label">Academic Year <span class="text-danger">*</span></label>
                        <select name="academic_year_id" id="academic_year_id" class="form-select @error('academic_year_id') is-invalid @enderror" required>
                            <option value="">Select Academic Year</option>
                            @foreach($academicYears as $year)
                                <option value="{{ $year->id }}"
                                    {{ old('academic_year_id', $payment->academic_year_id ?? '') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="semester" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror" required>
                            <option value="">Select Semester</option>
                            @foreach($semesters as $semester)
                                <option value="{{ $semester }}"
                                    {{ old('semester', $payment->semester ?? '') == $semester ? 'selected' : '' }}>
                                    {{ $semester }}
                                </option>
                            @endforeach
                        </select>
                        @error('semester')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="amount" class="form-label">Full Total Amount</label>
                        <input type="number" id="amount" name="amount" class="form-control" placeholder="00.0">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Payment Details</h6>
                <button type="button" class="btn btn-sm btn-primary" onclick="addPaymentDetail()">
                    <i class="bi bi-plus-circle"></i> Add Detail
                </button>
            </div>
            <div class="card-body">
                <div id="payment-details-container">
                    @if(isset($payment) && $payment->paymentDetails->count() > 0)
                        @foreach($payment->paymentDetails as $index => $detail)
                            @include('paymentDetail._form', ['index' => $index, 'detail' => $detail])
                        @endforeach
                    @else
                        @include('paymentDetail._form', ['index' => 0])
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Information -->
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">Payment Information</h6>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="payment_type" class="form-label">Payment Type <span class="text-danger">*</span></label>
                        <select name="payment_type" id="payment_type" class="form-select @error('payment_type') is-invalid @enderror" required>
                            <option value="">Select Payment Type</option>
                            @foreach($paymentTypes as $type)
                                <option value="{{ $type }}"
                                    {{ old('payment_type', $payment->payment_type ?? '') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date" id="payment_date"
                            class="form-control @error('payment_date') is-invalid @enderror"
                            value="{{ old('payment_date', isset($payment) ? $payment->payment_date->format('Y-m-d') : '') }}"
                            required>
                        @error('payment_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="">Select Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}"
                                    {{ old('status', $payment->status ?? '') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="total_amount" class="form-label">Total Amount</label>
                        <input type="text" id="total_amount" class="form-control" readonly
                            value="{{ isset($payment) ? number_format($payment->amount, 2) : '0.00' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

document.getElementById('student_search').addEventListener('input', function() {
    let query = this.value;
    let resultBox = document.getElementById('student_search_result');

    if (query.length >= 3) {
        fetch(`/student/search?query=${query}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                resultBox.innerHTML = ""; // Clear previous results
                if (data.length > 0) {
                    resultBox.style.display = 'block'; // Show results box
                    data.forEach(student => {
                        let div = document.createElement("div");
                        div.classList.add("list-group-item", "list-group-item-action", "search-item");
                        div.innerHTML = `${student.first_name} ${student.last_name}`;
                        div.onclick = function() {
                            document.getElementById('student_search').value = `${student.first_name} ${student.last_name}`;
                            document.getElementById('student_id').value = student.id;
                            resultBox.innerHTML = ""; // Clear the results after selection
                            resultBox.style.display = 'none'; // Hide the results box
                        };
                        resultBox.appendChild(div);
                    });
                } else {
                    resultBox.innerHTML = '<div class="list-group-item">No student on this matricule</div>';
                    resultBox.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('There was an error with the fetch operation:', error);
                alert('Une erreur est survenue, veuillez réessayer.');
            });
    } else {
        resultBox.innerHTML = ""; // Clear results when query is too short
        resultBox.style.display = 'none'; // Hide the results box
    }
});


let detailIndex = {{ isset($payment) ? $payment->paymentDetails->count() : 1 }};

function addPaymentDetail() {
    fetch('{{ route("payment.detailform") }}?index=' + detailIndex)
        .then(response => response.text())
        .then(html => {
            document.getElementById('payment-details-container').insertAdjacentHTML('beforeend', html);
            detailIndex++;
            updateTotalAmount();
        });
}

function removePaymentDetail(element) {
    element.closest('.payment-detail-row').remove();
    updateTotalAmount();
}

function updateTotalAmount() {
    const amounts = document.querySelectorAll('input[name^="payment_details"][name$="[amount]"]');
    const total = Array.from(amounts).reduce((sum, input) => sum + (parseFloat(input.value) || 0), 0);
    document.getElementById('total_amount').value = total.toFixed(2);
}

document.addEventListener('input', function(e) {
    if (e.target.matches('input[name^="payment_details"][name$="[amount]"]')) {
        updateTotalAmount();
}})
</script>
