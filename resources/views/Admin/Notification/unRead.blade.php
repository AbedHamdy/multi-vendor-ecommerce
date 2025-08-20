{{-- @dd($notifications); --}}
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
                    <form action="{{ route('admin.markAllRead_notifications') }}" method="POST" class="d-inline">
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
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Shipping Address</th>
                            <th>Total Price</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notifications as $index => $notification)
                            <tr class="table-warning">
                                <td>{{ $index + 1 + ($notifications->currentPage() - 1) * $notifications->perPage() }}</td>
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
                                    </div>
                                </td>
                                <td>{{ $notification->data['phone'] ?? 'N/A' }}</td>
                                <td>{{ $notification->data['shipping_address'] ?? 'N/A' }}</td>
                                <td>
                                    <span class="fw-bold text-success">
                                        {{ number_format($notification->data['total_price'] ?? 0, 2) }} EGP
                                    </span>
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
                                    <a href="{{ {{ route('admin.order.show', $notification->data['order_id']) }} }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-bell-slash fa-3x mb-3 text-muted"></i>
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

    </div>
@endsection
