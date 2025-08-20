@extends('layouts.app')

@section('title', 'All Notifications')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0 text-dark">
                <i class="fas fa-bell me-2 text-primary"></i>
                All Notifications
                @if ($notifications->total() > 0)
                    <span class="badge bg-primary ms-2">{{ $notifications->total() }}</span>
                @endif
            </h3>
            <div class="d-flex gap-2">
                <a href="" class="btn btn-outline-warning">
                    <i class="fas fa-bell me-1"></i> Unread Only
                </a>
                @if ($notifications->count() > 0)
                    <form action="" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check-double me-1"></i> Mark All Read
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Table --}}
        <div class="card shadow border-0">
            <div class="card-body table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
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
                            <tr class="{{ $notification->read_at ? '' : 'table-warning' }}">
                                <td>{{ $index + 1 + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
                                <td>
                                    <strong>{{ $notification->data['user_name'] ?? 'Unknown' }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $notification->data['user_email'] ?? 'N/A' }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $notification->data['product_name'] ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $notification->data['quantity'] ?? 0 }}</td>
                                <td>
                                    {{ number_format($notification->data['price'] ?? 0, 2) }} EGP
                                    @if (!empty($notification->data['discount']))
                                        <br><small class="text-danger">{{ $notification->data['discount'] }}% off</small>
                                    @endif
                                </td>
                                <td>
                                    {{ ucfirst($notification->data['payment_method'] ?? 'N/A') }}
                                </td>
                                <td>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-outline-primary">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-5">
                                    <i class="fas fa-bell-slash fa-3x mb-3"></i>
                                    <h5>No notifications</h5>
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

    </div>
@endsection
