@extends('Client.layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="icon-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="icon-times-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <strong>There were some problems:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="card-title mb-4 text-center">Checkout</h2>
                    <p class="text-center text-muted mb-4">Please provide your shipping details and select a payment method</p>

                    <form action="{{ route('client.checkout.process') }}" method="POST">
                        @csrf
                        <input type="hidden" name="price" value="{{ $totalPrice ?? old('price') }}">

                        <!-- Shipping Address -->
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <input type="text" class="form-control" id="shipping_address" name="shipping_address"
                                   placeholder="Enter your shipping address"
                                   value="{{ old('shipping_address') }}" required>
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   placeholder="Enter your contact number"
                                   value="{{ old('phone') }}" required>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>Select a payment method</option>
                                <option value="stripe" {{ old('payment_method') == 'stripe' ? 'selected' : '' }}>Credit / Debit Card (Stripe)</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash on Delivery</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>
                                Complete Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('view_cart') }}" class="btn btn-link text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Cart
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
