@extends('layouts.app')

@section('title', 'Dashboard Vendor')

@section('content')

    {{-- التنبيهات --}}
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

    {{-- Current Package --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Current Package</h3>
                        @if ($package)
                            <p><strong>Name:</strong> {{ $package->name }}</p>
                            <p><strong>Price:</strong> {{ $package->price }} {{ $package->currency ?? 'EGP' }}</p>
                            <p><strong>Duration:</strong> {{ $package->duration }} Month</p>
                            <p><strong>Number of Features:</strong> {{ count($package->features ?? []) }}</p>
                        @else
                            <p class="text-danger">No package subscription yet.</p>
                        @endif
                    </div>
                    <div class="card-icon green">
                        <i class="fas fa-crown fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- إحصائيات سريعة --}}
    <div class="row gy-4">
        {{-- Total Products --}}
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Total Products</h3>
                        {{-- <h2>{{ $vendor->products()->count() }}</h2> --}}
                    </div>
                    <div class="card-icon blue">
                        <i class="fas fa-box-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Orders --}}
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Total Orders</h3>
                        {{-- <h2>{{ $vendor->orders()->count() }}</h2> --}}
                    </div>
                    <div class="card-icon orange">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Orders --}}
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Pending Orders</h3>
                        {{-- <h2>{{ $vendor->orders()->where('status', 'pending')->count() }}</h2> --}}
                    </div>
                    <div class="card-icon purple">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Departments --}}
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Total Departments</h3>
                        {{-- <h2>{{ $vendor->departments()->count() }}</h2> --}}
                    </div>
                    <div class="card-icon green">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- المنتجات الأكثر مبيعًا --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Top Selling Products</h3>
                </div>
                <div class="card-body">
                    {{-- @if($topProducts->isNotEmpty())
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Orders</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->orders_count }}</td>
                                        <td>{{ $product->stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No sales yet.</p>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>

    {{-- أحدث الطلبات --}}
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3>Latest Orders</h3>
                </div>
                <div class="card-body">
                    {{-- @if($latestOrders->isNotEmpty())
                        <table class="table table-striped text-center">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>
                                            <span class="badge
                                                @if($order->status=='pending') bg-warning
                                                @elseif($order->status=='completed') bg-success
                                                @else bg-secondary @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $order->total }} EGP</td>
                                        <td>{{ $order->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">No orders yet.</p>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>

@endsection
