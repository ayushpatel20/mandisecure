@extends('layouts.app')

@section('title', 'Payment Settings — MandiSecure Admin')

@section('sidebar')
    @include('admin.partials.sidebar', ['active' => 'settings'])
@endsection

@section('content')

    <div class="mb-4">
        <h4 class="mb-0 fw-bold"><i class="bi bi-gear me-2 text-success"></i>Payment Settings</h4>
        <small class="text-muted">Configure UPI ID and bank details shown to buyers during checkout</small>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <form method="POST" action="{{ route('admin.settings.payment.update') }}">
                @csrf

                {{-- UPI --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-phone me-1 text-success"></i> UPI Payment
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-semibold">UPI ID</label>
                        <input type="text" name="payment_upi_id"
                               class="form-control @error('payment_upi_id') is-invalid @enderror"
                               value="{{ old('payment_upi_id', $settings['payment_upi_id']) }}"
                               placeholder="e.g. mandisecure@ybl">
                        <div class="form-text">Buyers will be asked to transfer to this UPI ID.</div>
                        @error('payment_upi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Bank Transfer --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white fw-semibold">
                        <i class="bi bi-bank me-1 text-success"></i> Bank Transfer
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-12">
                                <label class="form-label fw-semibold">Account Holder Name</label>
                                <input type="text" name="payment_bank_account_holder"
                                       class="form-control @error('payment_bank_account_holder') is-invalid @enderror"
                                       value="{{ old('payment_bank_account_holder', $settings['payment_bank_account_holder']) }}"
                                       placeholder="e.g. MandiSecure Private Limited">
                                @error('payment_bank_account_holder')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Account Number</label>
                                <input type="text" name="payment_bank_account_number"
                                       class="form-control @error('payment_bank_account_number') is-invalid @enderror"
                                       value="{{ old('payment_bank_account_number', $settings['payment_bank_account_number']) }}"
                                       placeholder="e.g. 1234567890">
                                @error('payment_bank_account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">IFSC Code</label>
                                <input type="text" name="payment_bank_ifsc"
                                       class="form-control @error('payment_bank_ifsc') is-invalid @enderror"
                                       value="{{ old('payment_bank_ifsc', $settings['payment_bank_ifsc']) }}"
                                       placeholder="e.g. SBIN0001234">
                                @error('payment_bank_ifsc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Bank Name</label>
                                <input type="text" name="payment_bank_name"
                                       class="form-control @error('payment_bank_name') is-invalid @enderror"
                                       value="{{ old('payment_bank_name', $settings['payment_bank_name']) }}"
                                       placeholder="e.g. State Bank of India">
                                @error('payment_bank_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save me-1"></i> Save Settings
                    </button>
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>

        {{-- Live Preview --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm sticky-top" style="top:1rem">
                <div class="card-header bg-white fw-semibold small">
                    <i class="bi bi-eye me-1 text-success"></i> Live Preview
                </div>
                <div class="card-body">
                    <div class="small text-muted mb-2">As buyers will see it:</div>

                    <div class="mb-3 p-3 bg-light rounded-3">
                        <div class="fw-semibold small mb-2">
                            <i class="bi bi-phone text-success me-1"></i> UPI
                        </div>
                        <div class="text-center">
                            <div class="border border-success rounded-3 px-3 py-1 d-inline-block fw-bold text-success" id="preview_upi">
                                {{ $settings['payment_upi_id'] ?: '—' }}
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-light rounded-3">
                        <div class="fw-semibold small mb-2">
                            <i class="bi bi-bank text-success me-1"></i> Bank Transfer
                        </div>
                        <table class="table table-sm table-borderless mb-0 small">
                            <tr>
                                <td class="text-muted pe-3">Holder</td>
                                <td class="fw-semibold" id="preview_holder">{{ $settings['payment_bank_account_holder'] ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Account</td>
                                <td class="fw-semibold" id="preview_acc">{{ $settings['payment_bank_account_number'] ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">IFSC</td>
                                <td class="fw-semibold" id="preview_ifsc">{{ $settings['payment_bank_ifsc'] ?: '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Bank</td>
                                <td class="fw-semibold" id="preview_bank">{{ $settings['payment_bank_name'] ?: '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
function bindPreview(inputName, previewId) {
    const input = document.querySelector('[name="' + inputName + '"]');
    const preview = document.getElementById(previewId);
    if (!input || !preview) return;
    input.addEventListener('input', () => { preview.textContent = input.value || '—'; });
}
bindPreview('payment_upi_id', 'preview_upi');
bindPreview('payment_bank_account_holder', 'preview_holder');
bindPreview('payment_bank_account_number', 'preview_acc');
bindPreview('payment_bank_ifsc', 'preview_ifsc');
bindPreview('payment_bank_name', 'preview_bank');
</script>
@endpush
