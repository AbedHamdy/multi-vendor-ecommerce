{{-- @dd($orders->get(1)) --}}
@extends('Client.layouts.app')
@section('title', 'Shopping Cart')

@push('styles')
    <style>
        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .product-image {
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            background: #f8f9fa;
        }

        .quantity-controls button {
            border: none;
            background: transparent;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .quantity-controls button:hover {
            background: #e9ecef;
        }

        .quantity-controls input {
            border: none;
            text-align: center;
            width: 50px;
            background: transparent;
            font-weight: 600;
        }

        .specs-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin: 2px;
            display: inline-block;
        }

        .price-tag {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            font-size: 1.2em;
        }

        .empty-cart {
            text-align: center;
            padding: 100px 0;
        }

        .empty-cart i {
            font-size: 120px;
            color: #dee2e6;
            margin-bottom: 30px;
        }

        .cart-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: white;
            position: sticky;
            top: 20px;
        }

        /* تعديل زر الحذف */
        .remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #dc3545;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #fff;
            font-size: 16px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        }

        .remove-btn:hover {
            background: #c82333;
            transform: scale(1.15) rotate(10deg);
        }

        .continue-shopping {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .continue-shopping:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .checkout-btn {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            color: white;
            padding: 15px 0;
            border-radius: 10px;
            width: 100%;
            font-size: 18px;
            font-weight: 600;
            transition: transform 0.3s ease;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-4">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
            </ol>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="icon-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="icon-times-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container py-5">
        <div class="page-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-shopping-cart" style="font-size: 2.5rem; color: #667eea;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1">Shopping Cart</h1>
                        <p class="text-muted mb-0">Review your items before checkout</p>
                    </div>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge bg-primary fs-6">{{ $orders->total() }} Items</span>
                </div>
            </div>
        </div>

        @if ($orders->count() > 0)
            <div class="row">
                <!-- Cart Items -->
                <div class="col-lg-8">
                    @php
                        $totalPrice = 0;
                    @endphp
                    @foreach ($orders as $cart)
                        <div class="card cart-item mb-4 border-0 shadow-sm position-relative">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-3 col-sm-4 text-center">
                                        @if ($cart->product->images->isNotEmpty())
                                            <img src="{{ asset('images/products/' . $cart->product->images->first()->image) }}"
                                                alt="{{ $cart->product->name }}" class="img-fluid product-image"
                                                style="max-height: 120px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center product-image"
                                                style="height: 120px; width: 100%;">
                                                <i class="fas fa-image text-muted fa-3x"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-6 col-sm-8">
                                        <h5 class="mb-2">{{ $cart->product->name }}</h5>
                                        <p class="text-muted mb-3">{{ Str::limit($cart->product->description, 100) }}</p>

                                        @if (!empty($cart->specs))
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-2">Specifications:</small>
                                                @foreach ($cart->specs as $attributeName => $value)
                                                    <span class="specs-badge">{{ $attributeName }}:
                                                        {{ $value }}</span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="d-flex align-items-center">
                                            <span class="me-3">Quantity:</span>
                                            <div class="quantity-controls">
                                                <input type="number" value="{{ $cart->quantity }}" readonly
                                                    style="border:none; text-align:center; width:50px; background:transparent; font-weight:600;">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12 text-md-end text-center mt-3 mt-md-0">
                                        <div class="price-info">
                                            <div class="price-tag">${{ number_format($cart->product->price, 2) }}</div>
                                            <small class="text-muted">per item</small>
                                            @if ($cart->product->discount)
                                                <div class="text-success small mb-1">
                                                    Discount: {{ $cart->product->discount }}%
                                                </div>
                                            @endif
                                            <hr>
                                            <div class="text-muted small" style="text-decoration: line-through;">
                                                ${{ $cart->product->price * $cart->quantity }}
                                            </div>
                                            @php
                                                $finalPrice =
                                                    $cart->product->price *
                                                    (1 - ($cart->product->discount ?? 0) / 100) *
                                                    $cart->quantity;
                                                $totalPrice += $finalPrice;
                                            @endphp
                                            <div class="h5 mb-0 text-primary">
                                                <strong>${{ $finalPrice }}</strong>
                                            </div>
                                            <small class="text-muted">Total after discount</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mb-5">
                                <form action="{{ route('cart.remove', $cart->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-btn" onclick="return confirm('Are you sure you want to remove this product from your cart?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    @if ($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>

                <!-- Cart Summary -->
                @php
                    $taxRate = 10;
                    $taxAmount = ($totalPrice * $taxRate) / 100;
                    $finalTotal = $totalPrice + $taxAmount;
                @endphp

                <div class="col-lg-4">
                    <div class="cart-summary p-4">
                        <h4 class="mb-4">Order Summary</h4>
                        <div class="summary-item d-flex justify-content-between mb-3">
                            <span>Subtotal ({{ $orders->sum('quantity') }} items)</span>
                            <span>$ {{ $totalPrice }}</span>
                        </div>
                        <div class="summary-item d-flex justify-content-between mb-3">
                            <span>Shipping</span>
                            <span>$ 10</span>
                        </div>
                        <div class="summary-item d-flex justify-content-between mb-3">
                            <span>Tax</span>
                            <span>% 10</span>
                        </div>
                        <hr class="bg-white">
                        <div class="summary-total d-flex justify-content-between mb-4">
                            <h5>Total</h5>
                            <h5>$ {{ $finalTotal }}</h5>
                        </div>
                        <div class="text-center mb-3">
                            <a href="{{ route('select_payment') }}" class="btn btn-primary checkout-btn px-5 py-3">
                                <i class="fas fa-credit-card me-2"></i> Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3 class="mb-3">Your cart is empty</h3>
                <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
                <a href="" class="continue-shopping">
                    <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function updateQuantity(cartId, change)
            {
                fetch(`/cart/update/${cartId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            change: change
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }

            document.addEventListener('DOMContentLoaded', function() {
                const cartItems = document.querySelectorAll('.cart-item');
                cartItems.forEach((item, index) => {
                    item.style.opacity = '0';
                    item.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        item.style.transition = 'all 0.5s ease';
                        item.style.opacity = '1';
                        item.style.transform = 'translateY(0)';
                    }, index * 100);
                });
            });
        </script>
    @endpush
@endsection
