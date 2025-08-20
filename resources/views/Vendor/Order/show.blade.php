@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold m-0 text-dark">
                    <i class="fas fa-receipt me-2 text-primary"></i>
                    Order
                </h3>
                <small class="text-muted">Order placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</small>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('all_orders') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Orders
                </a>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            {{-- Order Status & Actions --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Order Status
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                            $statusClass = match ($order->status) {
                                'pending' => 'bg-warning',
                                'paid' => 'bg-primary',
                                'shipped' => 'bg-secondary',
                                'completed' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                default => 'bg-dark',
                            };
                        @endphp
                        <div class="text-center mb-3">
                            <span class="badge {{ $statusClass }} fs-5 px-3 py-2">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        {{-- Status Timeline --}}
                        <div class="timeline">
                            {{-- Order Placed يظهر دايمًا --}}
                            <div class="timeline-item completed">
                                <div class="timeline-marker"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Order Placed</h6>
                                    <small class="text-muted">{{ $order->created_at->format('M d, Y - H:i') }}</small>
                                </div>
                            </div>

                            @if ($order->status == 'cancelled')
                                {{-- فقط حالة Cancelled --}}
                                <div class="timeline-item completed">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Order Cancelled</h6>
                                        <small class="text-muted">{{ $order->updated_at->format('M d, Y - H:i') }}</small>
                                    </div>
                                </div>
                            @else
                                {{-- باقي الحالات الطبيعية --}}
                                <div
                                    class="timeline-item {{ $order->status == 'paid' || $order->status == 'shipped' || $order->status == 'completed' ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Payment Completed</h6>
                                        @if ($order->status == 'paid' || $order->status == 'shipped' || $order->status == 'completed')
                                            <small
                                                class="text-muted">{{ $order->updated_at->format('M d, Y - H:i') }}</small>
                                        @else
                                            <small class="text-muted">Pending payment</small>
                                        @endif
                                    </div>
                                </div>

                                <div
                                    class="timeline-item {{ $order->status == 'shipped' || $order->status == 'completed' ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Order Shipped</h6>
                                        @if ($order->status == 'shipped' || $order->status == 'completed')
                                            <small
                                                class="text-muted">{{ $order->shipped_at ? $order->shipped_at->format('M d, Y - H:i') : $order->updated_at->format('M d, Y - H:i') }}</small>
                                        @else
                                            <small class="text-muted">Not shipped yet</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="timeline-item {{ $order->status == 'completed' ? 'completed' : '' }}">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Order Completed</h6>
                                        @if ($order->status == 'completed')
                                            <small
                                                class="text-muted">{{ $order->delivered_at ? $order->delivered_at->format('M d, Y - H:i') : $order->updated_at->format('M d, Y - H:i') }}</small>
                                        @else
                                            <small class="text-muted">Not completed yet</small>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-8">
                {{-- Customer Information --}}
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Customer Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Customer Name</h6>
                                <p class="mb-3">{{ $order->user->name ?? 'Guest Customer' }}</p>

                                <h6 class="text-muted mb-1">Email Address</h6>
                                <p class="mb-3">{{ $order->user->email ?? $order->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Phone Number</h6>
                                <p class="mb-3">{{ $order->phone }}</p>

                                <h6 class="text-muted mb-1">Payment Method</h6>
                                @php
                                    $paymentMethod = $order->payment_method ?? 'unknown';
                                    $badgeClass = match ($paymentMethod) {
                                        'stripe' => 'bg-primary',
                                        'paypal' => 'bg-info',
                                        'cash_on_delivery' => 'bg-success',
                                        'bank_transfer' => 'bg-warning',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6">
                                    <i
                                        class="fas fa-credit-card me-1"></i>{{ ucfirst(str_replace('_', ' ', $paymentMethod)) }}
                                </span>
                            </div>
                        </div>

                        @if ($order->shipping_address)
                            <hr>
                            <h6 class="text-muted mb-1">Shipping Address</h6>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                        @endif
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2"></i>Order Items
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        {{-- <th>Unit Price</th> --}}
                                        <th>Total After Discount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->product->images)
                                                    <img src="{{ asset('images/products/' . $item->product->images->first()->image) }}"
                                                        alt="{{ $item->product->name }}" class="rounded me-3"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $item->quantity }}</span>
                                        </td>
                                        <td class="fw-bold text-success">{{ number_format($item->price, 2) }} EGP</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -15px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #e9ecef;
            border: 2px solid #fff;
        }

        .timeline-item.completed .timeline-marker {
            background: #198754;
        }

        .timeline-content h6 {
            color: #495057;
            font-size: 14px;
        }

        .timeline-item.completed .timeline-content h6 {
            color: #198754;
        }

        .card {
            border-radius: 10px;
        }

        .badge {
            border-radius: 8px;
        }
    </style>
@endpush
