@extends('layouts.app')

@section('title', 'Orders')

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0 text-dark">
                <i class="fas fa-shopping-cart me-2 text-primary"></i>
                Orders
                @if ($orders->total() > 0)
                    <span class="badge bg-dark ms-2">{{ $orders->total() }}</span>
                @endif
            </h3>

            <div class="mb-3">
                <form method="GET" action="{{ route('admin.all_orders') }}" class="d-flex align-items-center gap-2">
                    <div class="input-group">
                        <label class="input-group-text bg-dark text-white" for="status"><i
                                class="fas fa-filter"></i></label>
                        <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                            <option value="">-- Filter by Status --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>

                    @if (request('status'))
                        <a href="{{ route('admin.all_orders') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                    @endif
                </form>
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
                            <th>Status</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                <td>
                                    <div>
                                        <strong class="text-dark">{{ $order->user->name ?? 'Unknown' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>{{ $order->user->email ?? 'N/A' }}
                                        </small>
                                    </div>
                                </td>
                                <td>{{ $order->phone ?? 'N/A' }}</td>
                                <td>{{ $order->shipping_address ?? 'N/A' }}</td>
                                <td>
                                    <span class="fw-bold text-success">
                                        {{ number_format($order->total_price, 2) }} EGP
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $paymentMethod = $order->payment_method ?? 'unknown';
                                        $badgeClass = match ($paymentMethod) {
                                            'stripe' => 'bg-primary',
                                            'paypal' => 'bg-info',
                                            'cod' => 'bg-success',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        <i class="fas fa-credit-card me-1"></i>{{ ucfirst($paymentMethod) }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $status = $order->status ?? 'pending';
                                        $statusClass = match ($status) {
                                            'pending' => 'bg-warning',
                                            'shipped' => 'bg-info',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $order->created_at->diffForHumans() }}
                                        <br>
                                        {{ $order->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.order.show', $order->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-5">
                                    <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                                    <h5>No orders found</h5>
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
