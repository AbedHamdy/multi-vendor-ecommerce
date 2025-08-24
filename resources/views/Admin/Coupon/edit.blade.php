@extends("layouts.app")

@section("title", "Edit Coupon")

@section("content")
<div class="container">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0 text-dark">Edit Coupon</h3>
        <a href="{{ route('coupon.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <div class="card shadow border-0">
        <div class="card-body">
            <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Coupon Code</label>
                        <input type="text" class="form-control" name="code" id="code" value="{{ old('code', $coupon->code) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="type" class="form-label">Coupon Type</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="general" {{ old('type', $coupon->type) == 'general' ? 'selected' : '' }}>General</option>
                            <option value="welcome" {{ old('type', $coupon->type) == 'welcome' ? 'selected' : '' }}>Welcome</option>
                            <option value="loyalty" {{ old('type', $coupon->type) == 'loyalty' ? 'selected' : '' }}>Loyalty</option>
                            <option value="event" {{ old('type', $coupon->type) == 'event' ? 'selected' : '' }}>Event/Seasonal</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="discount_type" class="form-label">Discount Type</label>
                        <select name="discount_type" id="discount_type" class="form-select" required>
                            <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="percent" {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>Percent</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="value" class="form-label">Value</label>
                        <input type="number" step="0.01" class="form-control" name="value" id="value" value="{{ old('value', $coupon->value) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label for="usage_limit" class="form-label">Usage Limit (optional)</label>
                        <input type="number" class="form-control" name="usage_limit" id="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="min_order_amount" class="form-label">Minimum Order Amount (optional)</label>
                        <input type="number" step="0.01" class="form-control" name="min_order_amount" id="min_order_amount" value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                    </div>

                    <div class="col-md-6">
                        <label for="start_date" class="form-label">Start Date (optional)</label>
                        <input type="datetime-local" class="form-control" name="start_date" id="start_date" value="{{ old('start_date', $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="col-md-6">
                        <label for="end_date" class="form-label">End Date (optional)</label>
                        <input type="datetime-local" class="form-control" name="end_date" id="end_date" value="{{ old('end_date', $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mt-3 col-1 form-check text-center">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary px-5">Update Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
