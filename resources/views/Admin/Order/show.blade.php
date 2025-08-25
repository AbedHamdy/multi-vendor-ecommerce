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
            <a href="{{ route('admin.all_orders') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Orders
            </a>
        </div>

        <div class="row">
            {{-- Order Status --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Order Status</h5>
                    </div>
                    <div class="card-body text-center">
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
                        <span class="badge {{ $statusClass }} fs-5 px-3 py-2">{{ ucfirst($order->status) }}</span>

                        {{-- Status Update Form --}}
                        @if (!in_array($order->status, ['completed', 'cancelled']))
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="mt-3">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select mb-2">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-success w-100">
                                    <i class="fas fa-sync-alt me-1"></i> Update Status
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Customer Info + Products --}}
            <div class="col-md-8">
                {{-- Customer Information --}}
                <div class="card shadow border-0 mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $order->user->name ?? 'Guest' }}</p>
                        <p><strong>Email:</strong> {{ $order->user->email ?? $order->email }}</p>
                        <p><strong>Phone:</strong> {{ $order->phone }}</p>
                        <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
                    </div>
                </div>

                {{-- Order Items --}}
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Order Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($item->product && $item->product->images->first())
                                                        <img src="{{ asset('images/products/' . $item->product->images->first()->image) }}"
                                                            alt="{{ $item->product->name }}" class="rounded me-2"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->product->name ?? 'Deleted Product' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="badge bg-primary">{{ $item->quantity }}</span></td>
                                            <td class="fw-bold text-success">
                                                {{ number_format($item->price) }} EGP</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="text-center mt-3">
                                <p class="mb-1"><small>Shipping: 10 EGP</small></p>
                                <p class="mb-1"><small>Tax (10%): {{ number_format($order->total_price * 0.10, 2) }} EGP</small></p>
                                <hr>
                                <h5 class="fw-bold mt-2">
                                    Total: {{ number_format($order->total_price, 2) }} EGP
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
