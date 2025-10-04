@extends('Client.layouts.app')
@section('title', 'Packages')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Packages</li>
            </ol>
        </div>
    </nav>

    <div class="page-content">
        <div class="container">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <!-- Page Title -->
            <div class="text-center mb-5">
                <h1 class="page-title">Our Featured Packages</h1>
                <p class="lead text-muted">Choose the plan that suits your needs</p>
            </div>

            <!-- Packages Display -->
            <div class="row justify-content-center">
                @forelse($packages as $package)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card package-card h-100 shadow-sm border-0">
                            <!-- Card Header -->
                            <div class="card-header bg-gradient-primary text-white text-center border-0 py-4">
                                @if($package->image)
                                    <div class="package-icon mb-3">
                                        <img src="{{ asset('storage/' . $package->image) }}"
                                             alt="{{ $package->name }}"
                                             class="img-fluid rounded-circle"
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="package-icon mb-3">
                                        <i class="fas fa-cube fa-3x"></i>
                                    </div>
                                @endif
                                <h3 class="card-title mb-0">{{ $package->name }}</h3>
                                @if($package->description)
                                    <p class="card-subtitle mt-2 opacity-75">{{ $package->description }}</p>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body d-flex flex-column">
                                <div class="text-center mb-4">
                                    <div class="price-display">
                                        <span class="price-currency">EGP</span>
                                        <span class="price-amount display-4 font-weight-bold text-primary">
                                            {{ number_format($package->price, 0) }}
                                        </span>
                                        <span class="price-period text-muted d-block">Per month</span>
                                    </div>
                                </div>

                                <!-- Features -->
                                @if($package->features && $package->features->count() > 0)
                                    <div class="features-list flex-grow-1">
                                        <h5 class="features-title mb-3">
                                            <i class="fas fa-star text-warning me-2"></i>
                                            Package Features
                                        </h5>
                                        <ul class="list-unstyled">
                                            @foreach($package->features as $feature)
                                                <li class="feature-item mb-2">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <span>{{ $feature->title }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="mt-4 pt-3 border-top">
                                    <div class="d-grid gap-2">
                                        {{-- <button class="btn btn-primary btn-lg"
                                                onclick="selectPackage({{ $package->id }})">
                                            <i class="fas fa-shopping-cart me-2"></i>
                                            Choose This Plan
                                        </button> --}}
                                        <button class="btn btn-outline-secondary"
                                                onclick="showPackageDetails({{ $package->id }})">
                                            <i class="fas fa-info-circle me-2"></i>
                                            View More Details
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Featured Badge -->
                            @if($package->is_featured)
                                <div class="package-badge">
                                    <span class="badge bg-warning text-dark">Most Popular</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-box-open fa-4x text-muted"></i>
                            </div>
                            <h3 class="text-muted">No packages available at the moment</h3>
                            <p class="text-muted">New packages will be added soon</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .package-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #7c4dff 0%, #536dfe 100%);
        }

        .price-amount {
            color: #536dfe !important;
        }

        .feature-item {
            padding: 8px 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .package-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }

        .package-icon img {
            border: 3px solid rgba(255,255,255,0.3);
        }

        .features-title {
            color: #495057;
            font-weight: 600;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-title {
            color: #2d3748;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .price-amount {
                font-size: 2rem !important;
            }

            .package-card {
                margin-bottom: 2rem;
            }
        }
    </style>

    <script>
        // function selectPackage(packageId) {
        //     console.log('Selected package:', packageId);
        //     Swal.fire({
        //         title: 'Package Selected',
        //         text: 'Redirecting to checkout...',
        //         icon: 'success',
        //         confirmButtonText: 'Continue'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             window.location.href = '/package/subscribe/' + packageId;
        //         }
        //     });
        // }

        function showPackageDetails(packageId) {
            console.log('Show details for package:', packageId);
            window.location.href = '/package/details/' + packageId;
        }

        function openChat() {
            console.log('Opening live chat...');
        }
    </script>
@endsection
