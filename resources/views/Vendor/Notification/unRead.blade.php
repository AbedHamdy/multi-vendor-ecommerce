@extends('layouts.app')

@section('title', 'Unread Notifications')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0 text-dark">
                <i class="fas fa-bell me-2 text-warning"></i>
                Unread Notifications
                @if ($notifications->total() > 0)
                    <span class="badge bg-danger ms-2">{{ $notifications->total() }}</span>
                @endif
            </h3>
            <div class="d-flex gap-2">
                @if ($notifications->count() > 0)
                    <form action="{{ route("markAllRead_notifications") }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-double me-1"></i> Mark All Read
                        </button>
                    </form>
                @endif
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

        {{-- Table --}}
        <div class="card shadow border-0">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            {{-- <th>Order ID</th> --}}
                            <th>Customer</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $index => $notification)
                            <tr class="table-warning">
                                <td>{{ $index + 1 + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                                {{-- <td>
                                <span class="badge bg-primary fs-6">#{{ $notification->data['order_id'] ?? 'N/A' }}</span>
                            </td> --}}
                                <td>
                                    <div>
                                        <strong
                                            class="text-dark">{{ $notification->data['user_name'] ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i
                                                class="fas fa-envelope me-1"></i>{{ $notification->data['user_email'] ?? 'N/A' }}
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i
                                                class="fas fa-phone me-1"></i>{{ $notification->data['customer_phone'] ?? 'N/A' }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge bg-info text-white">{{ $notification->data['product_name'] ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ $notification->data['quantity'] ?? 0 }}</span>
                                </td>
                                <td>
                                    <div>
                                        <span
                                            class="fw-bold text-success">{{ number_format($notification->data['price'] ?? 0, 2) }}
                                            EGP</span>
                                        @if (isset($notification->data['discount']) && $notification->data['discount'] > 0)
                                            <br>
                                            <small class="text-danger">
                                                <i
                                                    class="fas fa-percentage me-1"></i>{{ $notification->data['discount'] }}%
                                                discount
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $paymentMethod = $notification->data['payment_method'] ?? 'unknown';
                                        $badgeClass = match ($paymentMethod) {
                                            'stripe' => 'bg-primary',
                                            'paypal' => 'bg-info',
                                            'cash' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        <i class="fas fa-credit-card me-1"></i>{{ ucfirst($paymentMethod) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        <br>
                                        {{ $notification->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route("vendor.orders.show" , [$notification->data['order_id'] , $notification->data['product_id']]) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-bell-slash fa-3x mb-3 text-muted"></i>
                                    <br>
                                    <h5>No unread notifications</h5>
                                    <p class="mb-0">All caught up! You have no new notifications.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if ($notifications->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Statistics Card --}}
        @if ($notifications->count() > 0)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-shopping-cart fa-2x text-primary me-3"></i>
                                        <div>
                                            <h4 class="mb-0 text-primary">{{ $notifications->total() }}</h4>
                                            <small class="text-muted">New Orders</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-dollar-sign fa-2x text-success me-3"></i>
                                        <div>
                                            @php
                                                $totalRevenue = $notifications->sum(function ($notification) {
                                                    $price = $notification->data['price'] ?? 0;
                                                    $quantity = $notification->data['quantity'] ?? 0;
                                                    $discount = $notification->data['discount'] ?? 0; // نسبة الخصم %

                                                    $discountedPrice = $price - ($price * $discount) / 100;

                                                    return $discountedPrice * $quantity;
                                                });
                                            @endphp
                                            <h4 class="mb-0 text-success">{{ number_format($totalRevenue, 2) }}</h4>
                                            <small class="text-muted">Revenue (EGP)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-boxes fa-2x text-warning me-3"></i>
                                        <div>
                                            @php
                                                $totalQuantity = $notifications->sum(function ($notification) {
                                                    return $notification->data['quantity'] ?? 0;
                                                });
                                            @endphp
                                            <h4 class="mb-0 text-warning">{{ $totalQuantity }}</h4>
                                            <small class="text-muted">Items Sold</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <i class="fas fa-users fa-2x text-info me-3"></i>
                                        <div>
                                            @php
                                                $uniqueCustomers = $notifications->groupBy('data.user_email')->count();
                                            @endphp
                                            <h4 class="mb-0 text-info">{{ $uniqueCustomers }}</h4>
                                            <small class="text-muted">Customers</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
@endsection

@push('styles')
    <style>
        .table-warning {
            background-color: #fff3cd !important;
            border-left: 4px solid #ffc107;
        }

        .table-warning:hover {
            background-color: #ffeaa7 !important;
        }

        .card {
            border-radius: 10px;
        }

        .badge {
            border-radius: 8px;
        }

        .btn-group .btn {
            border-radius: 6px !important;
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>
@endpush
