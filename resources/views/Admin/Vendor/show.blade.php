@extends('layouts.app')

@section('title', 'Vendor Details')

@section('content')
    <div class="container py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0 text-dark">{{ $vendor->name }} - Details</h3>
            <a href="{{ route('vendor') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Vendors
            </a>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Vendor Info Card --}}
        <div class="card shadow border-0 mb-4">
            <div class="card-body">
                <div class="row align-items-start">
                    {{-- Logo --}}
                    <div class="col-md-3 text-center">
                        <img src="{{ asset('images/item-4.jpg') }}" class="img-fluid rounded mb-3 shadow"
                            style="max-height:150px;" alt="Vendor Logo">
                    </div>

                    {{-- Basic Info --}}
                    <div class="col-md-9">
                        <h4 class="fw-bold text-primary mb-3">{{ $vendor->name }}</h4>

                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <p class="mb-2">
                                    <i class="fas fa-envelope text-muted me-2"></i>
                                    <strong>Email:</strong> {{ $vendor->email ?? 'N/A' }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-building text-muted me-2"></i>
                                    <strong>Department:</strong> {{ $vendor->department->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="mb-2">
                                    <i class="fas fa-box text-muted me-2"></i>
                                    <strong>Package:</strong> {{ $vendor->package->name ?? 'No Package' }}
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-calendar text-muted me-2"></i>
                                    <strong>Joined:</strong> {{ $vendor->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        {{-- Package Details --}}
                        @if ($vendor->package)
                            <div class="border-top pt-3">
                                <h5 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-star text-warning me-2"></i>Package Details
                                </h5>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="bg-light p-3 rounded text-center">
                                            <i class="fas fa-tag text-primary fs-4 mb-2"></i>
                                            <h6 class="mb-1">Package Name</h6>
                                            <p class="mb-0 fw-semibold">{{ $vendor->package->name }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="bg-light p-3 rounded text-center">
                                            <i class="fas fa-dollar-sign text-success fs-4 mb-2"></i>
                                            <h6 class="mb-1">Price</h6>
                                            <p class="mb-0 fw-semibold text-success">${{ number_format($vendor->package->price, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="bg-light p-3 rounded text-center">
                                            <i class="fas fa-clock text-info fs-4 mb-2"></i>
                                            <h6 class="mb-1">Duration</h6>
                                            <p class="mb-0 fw-semibold">{{ $vendor->package->duration }} Months</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="border-top pt-3">
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    This vendor is not subscribed to any package.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Vendor Products --}}
        <div class="card shadow border-0">
            <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                <span>
                    <i class="fas fa-boxes me-2"></i>Vendor Products
                </span>
                <span class="badge bg-light text-primary">{{ $products->total() }} Products</span>
            </div>
            <div class="card-body p-0">
                @if ($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">#</th>
                                    <th>Product Name</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                    <th class="pe-3">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    <tr>
                                        <td class="ps-3">{{ $products->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-cube text-muted me-2"></i>
                                                <span class="fw-semibold">{{ $product->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-success">${{ number_format($product->price, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($product->stock > 10)
                                                <span class="badge bg-success">{{ $product->stock }}</span>
                                            @elseif($product->stock > 0)
                                                <span class="badge bg-warning">{{ $product->stock }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ $product->stock }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($product->discount > 0)
                                                <span class="badge bg-info">{{ $product->discount }}%</span>
                                            @else
                                                <span class="text-muted">No Discount</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->is_active)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-pause-circle me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="pe-3">
                                            <small class="text-muted">{{ $product->created_at->format('M d, Y') }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Links --}}
                    @if($products->hasPages())
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-center">
                                {{ $products->links() }}
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open text-muted" style="font-size: 3rem;"></i>
                        <h5 class="text-muted mt-3">No Products Available</h5>
                        <p class="text-muted">This vendor hasn't added any products yet.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
