@extends('layouts.app')
@section('title', 'Edit Coupon')

@section('content')
    <div class="container">
        <h3 class="fw-bold mb-4 text-dark text-center">Edit Coupon</h3>
        <div class="card shadow border-0">
            <div class="card-body">

                {{-- Alerts --}}
                @if (session('error'))
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

                <form action="{{ route('coupon.update', $coupon->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Row 1: Code (readonly) + Value --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="code" class="form-label fw-semibold">Coupon Code</label>
                            <input type="text" name="code" id="code" class="form-control"
                                value="{{ old('code', $coupon->code) }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="value" class="form-label fw-semibold">Discount Value (%)</label>
                            <input type="number" step="0.01" name="value" id="value" class="form-control" required
                                value="{{ old('value', $coupon->value) }}">
                        </div>
                    </div>

                    {{-- Row 2: Usage Limit + Active Status --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="usage_limit" class="form-label fw-semibold">Usage Limit</label>
                            <input type="number" name="usage_limit" id="usage_limit" class="form-control"
                                value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                placeholder="Leave empty for unlimited">
                        </div>
                        <div class="col-md-6">
                            <label for="is_active" class="form-label fw-semibold">Status</label>
                            <select name="is_active" id="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $coupon->is_active) == 1 ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0" {{ old('is_active', $coupon->is_active) == 0 ? 'selected' : '' }}>
                                    Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 3: Start & End Dates --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label fw-semibold">Start Date</label>
                            <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label fw-semibold">End Date</label>
                            <input type="datetime-local" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date', $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d\TH:i') : '') }}">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('coupon') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
