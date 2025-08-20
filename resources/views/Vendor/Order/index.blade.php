{{-- @dd($orders) --}}
@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0 text-dark">
                <i class="fas fa-shopping-bag me-2 text-primary"></i>
                My Orders
                @if ($orders->total() > 0)
                    <span class="badge bg-primary ms-2">{{ $orders->total() }}</span>
                @endif
            </h3>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="?status=pending">Pending Orders</a></li>
                        <li><a class="dropdown-item" href="?status=paid">paid Orders</a></li>
                        <li><a class="dropdown-item" href="?status=shipped">Shipped Orders</a></li>
                        <li><a class="dropdown-item" href="?status=completed">Completed Orders</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('all_orders') }}">All Orders</a></li>
                    </ul>
                </div>
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

        {{-- Orders Statistics Cards --}}
        @if ($orders->count() > 0)
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shopping-cart fa-2x me-3"></i>
                                <div>
                                    <h4 class="mb-0">{{ $orders->total() }}</h4>
                                    <small>Total Orders</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-dollar-sign fa-2x me-3"></i>
                                <div>
                                    @php
                                        $totalRevenue = 0;
                                        foreach ($orders as $order) {
                                            foreach ($order->items as $item) {
                                                $totalRevenue += $item->price;
                                            }
                                        }
                                    @endphp
                                    <h4 class="mb-0">{{ number_format($totalRevenue, 0) }}</h4>
                                    <small>Revenue (EGP)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-boxes fa-2x me-3"></i>
                                <div>
                                    @php
                                        $totalItems = 0;
                                        foreach ($orders as $order) {
                                            $totalItems += $order->items->sum('quantity');
                                        }
                                    @endphp
                                    <h4 class="mb-0">{{ $totalItems }}</h4>
                                    <small>Items Sold</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock fa-2x me-3"></i>
                                <div>
                                    @php
                                        $pendingOrders = $orders->where('status', 'pending')->count();
                                    @endphp
                                    <h4 class="mb-0">{{ $pendingOrders }}</h4>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Orders Table --}}
        <div class="card shadow border-0">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            {{-- <th>Order ID</th> --}}
                            <th>Customer</th>
                            <th>Products</th>
                            <th>Total Amount</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Order Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                {{-- <td>
                                    <span class="badge bg-primary fs-6">#{{ $order->id }}</span>
                                </td> --}}
                                <td>
                                    <div>
                                        <strong class="text-dark">{{ $order->user->name ?? 'Guest' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>{{ $order->user->email ?? $order->email }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>{{ $order->phone }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @foreach ($order->items as $item)
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-light text-dark me-2">{{ $item->quantity }}x</span>
                                                <small class="text-truncate" style="max-width: 150px;"
                                                    title="{{ $item->product->name }}">
                                                    {{ Str::limit($item->product->name, 20) }}
                                                </small>
                                            </div>
                                        @endforeach
                                        @if ($order->items->count() > 3)
                                            <small class="text-muted">+{{ $order->items->count() - 3 }} more items</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="fw-bold text-success fs-5">{{ number_format($order->items->sum('price'), 2) }}
                                        EGP</span>
                                    <br>
                                    <small class="text-muted">{{ $order->items->sum('quantity') }} items</small>
                                </td>
                                <td>
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
                                    <span class="badge {{ $badgeClass }}">
                                        <i
                                            class="fas fa-credit-card me-1"></i>{{ ucfirst(str_replace('_', ' ', $paymentMethod)) }}
                                    </span>
                                    <br>
                                    @if ($order->payment_status)
                                        @php
                                            $paymentStatusClass = match ($order->payment_status) {
                                                'paid' => 'text-success',
                                                'pending' => 'text-warning',
                                                'failed' => 'text-danger',
                                                default => 'text-muted',
                                            };
                                        @endphp
                                        <small class="{{ $paymentStatusClass }}">
                                            <i class="fas fa-circle me-1"
                                                style="font-size: 8px;"></i>{{ ucfirst($order->payment_status) }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match ($order->status) {
                                            'pending' => 'bg-warning',
                                            'confirmed' => 'bg-info',
                                            'processing' => 'bg-primary',
                                            'shipped' => 'bg-secondary',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-dark',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} fs-6">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('d/m/Y') }}
                                        <br>
                                        <i class="fas fa-clock me-1"></i>{{ $order->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('vendor.orders.show', [$order->id, $item->product->id]) }}">
                                                    <i class="fas fa-eye me-2 text-info"></i> View Details
                                                </a>
                                            </li>
                                            @if ($order->status === 'pending')
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('vendor.orders.confirm', [$order->id, $item->product->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-check me-2"></i> Confirm Order
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('vendor.orders.cancel', [$order->id, $item->product->id]) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <i class="fas fa-times me-2"></i> Cancel Order
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            @if ($order->status === 'paid')
                                                <li>
                                                    <form action="{{ route('vendor.orders.ship', [$order->id, $item->product->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-primary">
                                                            <i class="fas fa-shipping-fast me-2"></i> Mark as Shipped
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                            @if ($order->status === 'shipped')
                                                <li>
                                                    <form action="{{ route('vendor.orders.deliver', [$order->id, $item->product->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            <i class="fas fa-truck me-2"></i> Mark as Delivered
                                                        </button>
                                                    </form>
                                                </li>
                                            @endif

                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-shopping-bag fa-3x mb-3 text-muted"></i>
                                    <br>
                                    <h5>No orders found</h5>
                                    <p class="mb-0">You haven't received any orders yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if ($orders->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .badge {
            border-radius: 8px;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .dropdown-toggle::after {
            margin-left: 0.5em;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bg-primary {
            background-color: #0d6efd !important;
        }

        .bg-success {
            background-color: #198754 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
        }

        .bg-info {
            background-color: #0dcaf0 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
        }

        .fs-5 {
            font-size: 1.1rem !important;
        }

        .fs-6 {
            font-size: 0.9rem !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto-refresh every 5 minutes for new orders
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>
@endpush
