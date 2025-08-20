@extends("layouts.app")

@section("title", "Coupons")

@section("content")
<div class="container">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0 text-dark">Coupon List</h3>
        <a href="{{ route('coupon.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Coupon
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Code</th>
                        <th>Value</th>
                        <th>Usage Limit</th>
                        <th>Used Times</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coupons as $index => $coupon)
                        <tr class="text-center">
                            <td>{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $coupon->code }}</td>
                            <td>{{ number_format($coupon->value, 2) }}</td>
                            <td>{{ $coupon->usage_limit ?? '∞' }}</td>
                            <td>{{ $coupon->used_times }}</td>
                            <td>{{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d H:i') : '-' }}</td>
                            <td>
                                @if($coupon->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        {{-- <li>
                                            <a class="dropdown-item" href="{{ route('coupon.show', $coupon->id) }}">
                                                <i class="fas fa-eye me-2 text-info"></i> View
                                            </a>
                                        </li> --}}
                                        <li>
                                            <a class="dropdown-item" href="{{ route('coupon.edit', $coupon->id) }}">
                                                <i class="fas fa-edit me-2 text-warning"></i> Edit
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('coupon.delete', $coupon->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">No coupons available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
