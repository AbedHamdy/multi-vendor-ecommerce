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
                            <div class="product card h-100">
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

                                    <div class="product-price mt-auto">
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

                                    <div class="product-stock mt-2">
                                        @if ($product->stock > 0)
                                            <small class="text-success">Available ({{ $product->stock }} pcs)</small>
                                        @else
                                            <small class="text-danger">Out of Stock</small>
                                        @endif
                                    </div>

                                    <div class="product-action mt-3">
                                        @if ($product->stock > 0)
                                            <a href="#" class="btn btn-primary btn-add-cart"
                                                data-product-id="{{ $product->id }}">
                                                <i class="icon-shopping-cart"></i>Add to Cart
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" disabled>
                                                Out of Stock
                                            </button>
                                        @endif
                                        <a href="{{ route('view_product.show', $product->id) }}"
                                            class="btn btn-outline-primary btn-sm ml-2">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>No Products Found</h4>
                                <p class="text-muted">There are currently no products in this department.</p>
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
                border: 1px solid #eee;
                transition: all 0.3s ease;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            }

            .product:hover {
                transform: translateY(-5px);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            }

            .product-image {
                height: 200px;
                object-fit: cover;
                width: 100%;
            }

            .product-media {
                position: relative;
                overflow: hidden;
            }

            .product-label-group {
                position: absolute;
                top: 10px;
                left: 10px;
                z-index: 2;
            }

            .product-label {
                background: #dc3545;
                color: white;
                padding: 2px 8px;
                border-radius: 3px;
                font-size: 12px;
                font-weight: bold;
            }

            .product-title a {
                color: #333;
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
            }

            .product-title a:hover {
                color: #007bff;
            }

            .product-cat a {
                color: #6c757d;
                text-decoration: none;
                font-size: 12px;
            }

            .product-cat a:hover {
                color: #007bff;
            }

            .product-desc {
                font-size: 12px;
                color: #666;
            }

            .old-price {
                text-decoration: line-through;
                color: #999;
                font-size: 14px;
                margin-left: 10px;
            }

            .new-price {
                font-weight: bold;
                font-size: 16px;
            }

            .btn-add-cart {
                width: 100%;
                font-size: 12px;
                padding: 8px;
            }

            @media (max-width: 768px) {
                .product-image {
                    height: 150px;
                }

                .product-title a {
                    font-size: 13px;
                }

                .btn-add-cart {
                    font-size: 11px;
                    padding: 6px;
                }
            }
        </style>
    @endsection
