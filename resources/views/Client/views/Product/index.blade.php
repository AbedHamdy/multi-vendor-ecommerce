@extends('Client.layouts.app')
@section('title', 'Products')

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Products</li>
            </ol>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="icon-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="icon-times-circle"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <strong>There were some problems:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="page-content">
        <div class="container">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('view_product') }}">
                        <div class="form-group">
                            <label for="department_id">Filter by Department:</label>
                            <select name="department_id" id="department_id" class="form-control"
                                onchange="this.form.submit()">
                                <option value="">All Departments</option>
                                @foreach ($AllDepartments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-right">
                    <p class="text-muted">Total Products: {{ $products->total() }}</p>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row">
                @forelse($products as $product)
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div class="product card h-100 {{ $product->stock <= 0 ? 'out-of-stock' : '' }}">
                            <div class="product-media">
                                @if ($product->images->isNotEmpty())
                                    <a href="{{ route('product.show', $product->id) }}">
                                        <img src="{{ $product->images ? asset('images/products/' . $product->images->first()->image) : asset('images/products/elements/product-2.jpg') }}"
                                            alt="{{ $product->name }}" class="product-image card-img-top">
                                    </a>
                                @else
                                    <a href="{{ route('product.show', $product->id) }}">
                                        <img src="{{ asset('images/products/elements/product-2.jpg') }}" alt="No Image"
                                            class="product-image card-img-top">
                                    </a>
                                @endif

                                @if ($product->discount)
                                    <div class="product-label-group">
                                        <label class="product-label label-sale">-{{ $product->discount }}%</label>
                                    </div>
                                @endif

                                @if ($product->stock <= 0)
                                    <div class="product-overlay">
                                        <div class="out-of-stock-badge">
                                            <span>Out of Stock</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="product-body card-body d-flex flex-column">
                                <div class="product-cat">
                                    <a href="{{ route('view_product', ['department_id' => $product->department->id]) }}">
                                        {{ $product->department->name }}
                                    </a>
                                </div>

                                <h3 class="product-title">
                                    <a href="{{ route('view_product.show', $product->id) }}">{{ $product->name }}</a>
                                </h3>

                                @if ($product->description)
                                    <div class="product-desc">
                                        <p>{{ Str::limit($product->description, 80) }}</p>
                                    </div>
                                @endif

                                <div class="product-price mt-auto mb-2">
                                    @if ($product->discount)
                                        <span class="new-price text-primary">
                                            {{ number_format($product->price - ($product->price * $product->discount) / 100, 2) }}
                                            EGP
                                        </span>
                                        <span class="old-price">{{ number_format($product->price, 2) }} EGP</span>
                                    @else
                                        <span class="new-price text-primary">{{ number_format($product->price, 2) }}
                                            EGP</span>
                                    @endif
                                </div>

                                <div class="product-stock mb-3">
                                    @if ($product->stock > 0)
                                        <small class="text-success stock-available">
                                            <i class="icon-check-circle"></i> Available ({{ $product->stock }} pcs)
                                        </small>
                                    @else
                                        <small class="text-danger stock-empty">
                                            <i class="icon-times-circle"></i> Out of Stock
                                        </small>
                                    @endif
                                </div>

                                <div class="product-action mt-auto d-flex justify-content-center">
                                    <a href="{{ route('view_product.show', $product->id) }}"
                                        class="btn btn-outline-primary btn-view-details {{ $product->stock <= 0 ? 'btn-block' : '' }}">
                                        <i class="icon-eye"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <h4>No Products Found</h4>
                            <p class="text-muted">There are currently no products available in this department.</p>
                            <a href="{{ route('view_product') }}" class="btn btn-primary">View All Products</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        .product {
            border: 1px solid #e8e9ea;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        .product:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-color: #007bff;
        }

        .product.out-of-stock {
            opacity: 0.7;
        }

        .product.out-of-stock:hover {
            transform: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .product-image {
            height: 220px;
            object-fit: cover;
            width: 100%;
            transition: all 0.3s ease;
        }

        .product:hover .product-image {
            transform: scale(1.05);
        }

        .product-media {
            position: relative;
            overflow: hidden;
            background: #f8f9fa;
        }

        .product-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
        }

        .out-of-stock-badge {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }

        .product-label-group {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
        }

        .product-label {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(220, 53, 69, 0.3);
        }

        .product-body {
            padding: 20px 16px;
        }

        .product-title a {
            color: #2c3e50;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-title a:hover {
            color: #007bff;
        }

        .product-cat a {
            color: #6c757d;
            text-decoration: none;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        .product-cat a:hover {
            color: #007bff;
        }

        .product-desc {
            font-size: 13px;
            color: #666;
            line-height: 1.5;
            margin: 8px 0;
        }

        .product-price {
            margin: 12px 0;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 13px;
            margin-left: 8px;
        }

        .new-price {
            font-weight: bold;
            font-size: 16px;
            color: #007bff !important;
        }

        .product-stock {
            padding: 6px 0;
            border-radius: 4px;
        }

        .stock-available {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745 !important;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .stock-empty {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545 !important;
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
        }

        .btn-view-details {
            border-radius: 20px;
            font-size: 13px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1.5px solid #007bff;
        }

        .btn-view-details:hover {
            background: #007bff;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .product-image {
                height: 180px;
            }

            .product-title a {
                font-size: 14px;
            }

            .product-body {
                padding: 16px 12px;
            }

            .btn-view-details {
                font-size: 12px;
                padding: 6px 12px;
            }

            .new-price {
                font-size: 15px;
            }
        }

        @media (max-width: 576px) {
            .product-image {
                height: 160px;
            }

            .product-title a {
                font-size: 13px;
            }

            .out-of-stock-badge {
                font-size: 12px;
                padding: 6px 12px;
            }
        }

        /* Animation for stock status */
        .stock-available i,
        .stock-empty i {
            margin-right: 4px;
        }

        /* Hover effects */
        .product-cat a,
        .product-title a {
            transition: color 0.3s ease;
        }

        /* Loading state for images */
        .product-image {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
        }
    </style>
@endsection
