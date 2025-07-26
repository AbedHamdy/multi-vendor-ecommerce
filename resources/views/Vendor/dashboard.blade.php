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

    {{-- باقي الكروت --}}
    <div class="row gy-4">
        {{-- Total Packages --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="card-info">
                        <h3>Total Packages</h3>
                        <h2>.....</h2>
                    </div>
                    <div class="card-icon blue">
                        <i class="fas fa-box-open fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Departments --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="card-info">
                        <h3>Total Departments</h3>
                        <h2>.....</h2>
                    </div>
                    <div class="card-icon orange">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total Vendors --}}
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <div class="card-info">
                        <h3>Total Vendors</h3>
                        <h2>.....</h2>
                    </div>
                    <div class="card-icon purple">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
