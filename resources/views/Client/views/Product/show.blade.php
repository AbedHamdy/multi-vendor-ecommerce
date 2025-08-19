@extends('Client.layouts.app')
@section('title', $product->name)

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('view_product') }}">Products</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('view_product', ['department_id' => $product->department_id]) }}">
                        {{ $product->department->name }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
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
            <div class="product-details-top">
                <div class="row">
                    <!-- Product Images -->
                    <div class="col-md-6">
                        <div class="product-gallery">
                            @if ($product->images->isNotEmpty())
                                <figure class="product-main-image">
                                    @php
                                        $mainImage =
                                            $product->images->where('is_main', true)->first() ??
                                            $product->images->first();
                                    @endphp
                                    <img id="product-zoom" src="{{ asset('images/products/' . $mainImage->image) }}"
                                        data-zoom-image="{{ asset('images/products/' . $mainImage->image) }}"
                                        alt="{{ $product->name }}" class="img-fluid">

                                    @if ($product->discount)
                                        <span class="product-label label-sale">-{{ $product->discount }}%</span>
                                    @endif
                                </figure>

                                @if ($product->images->count() > 1)
                                    <div id="product-zoom-gallery" class="product-image-gallery">
                                        @foreach ($product->images as $image)
                                            <a class="product-gallery-item {{ $loop->first ? 'active' : '' }}"
                                                href="#" data-image="{{ asset('images/products/' . $image->image) }}"
                                                data-zoom-image="{{ asset('images/products/' . $image->image) }}">
                                                <img src="{{ asset('images/products/' . $image->image) }}"
                                                    alt="{{ $product->name }}" class="img-fluid">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <figure class="product-main-image">
                                    <img src="{{ asset('images/products/elements/product-2.jpg') }}" alt="No Image"
                                        class="img-fluid">
                                </figure>
                            @endif
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="col-md-6">
                        <div class="product-details">
                            <h1 class="product-title">{{ $product->name }}</h1>

                            <div class="product-price">
                                @if ($product->discount)
                                    <span class="new-price">
                                        {{ number_format($product->price - ($product->price * $product->discount) / 100, 2) }}
                                        EGP
                                    </span>
                                    <span class="old-price">{{ number_format($product->price, 2) }} EGP</span>
                                    <span class="save-badge">Save
                                        {{ number_format(($product->price * $product->discount) / 100, 2) }} EGP</span>
                                @else
                                    <span class="new-price">{{ number_format($product->price, 2) }} EGP</span>
                                @endif
                            </div>

                            @if ($product->description)
                                <div class="product-content">
                                    <p>{{ $product->description }}</p>
                                </div>
                            @endif

                            <div class="product-info-section">
                                <h5>Department</h5>
                                <div class="product-department">
                                    <a href="{{ route('view_product', ['department_id' => $product->department_id]) }}"
                                        class="department-badge">
                                        {{ $product->department->name }}
                                    </a>
                                </div>
                            </div>

                            <div class="product-info-section">
                                <h5>Availability</h5>
                                <div class="product-status">
                                    @if ($product->stock > 0)
                                        <span class="status-badge status-available">
                                            <i class="fas fa-check-circle"></i> Available ({{ $product->stock }} pieces)
                                        </span>
                                    @else
                                        <span class="status-badge status-out">
                                            <i class="fas fa-times-circle"></i> Out of Stock
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Enhanced Product Attributes -->
                            @if ($product->attributeValues->isNotEmpty())
                                <div class="product-attributes">
                                    <h4 class="attributes-title">
                                        <i class="fas fa-cogs"></i> Select Specifications
                                    </h4>
                                    <div class="specifications-selector">
                                        @foreach ($product->attributeValues->groupBy('attribute.name') as $attributeName => $values)
                                            <div class="specification-group">
                                                <label class="specification-label">
                                                    {{ $attributeName }}:
                                                </label>
                                                <div class="specification-options">
                                                    @if ($values->count() > 1)
                                                        @foreach ($values as $value)
                                                            <div class="specification-option">
                                                                <input type="radio"
                                                                    id="attr_{{ $attributeName }}_{{ $value->id }}"
                                                                    name="specification[{{ $attributeName }}]"
                                                                    value="{{ $value->attributeValue->value }}"
                                                                    data-attribute-id="{{ $value->attribute->id }}"
                                                                    data-attribute-value-id="{{ $value->attribute_value_id }}"
                                                                    data-attribute="{{ $attributeName }}"
                                                                    {{ $loop->first ? 'checked' : '' }}
                                                                    class="specification-radio">
                                                                <label for="attr_{{ $attributeName }}_{{ $value->id }}"
                                                                    class="specification-option-label">
                                                                    {{ $value->attributeValue->value }}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="specification-single">
                                                            <span class="badge badge-info single-spec-badge">
                                                                {{ $values->first()->attributeValue->value }}
                                                            </span>
                                                            <input type="hidden"
                                                                name="specification[{{ $attributeName }}]"
                                                                value="{{ $values->first()->attributeValue->value }}"
                                                                data-attribute="{{ $attributeName }}"
                                                                data-attribute-id="{{ $values->first()->attribute->id }}"
                                                                data-attribute-value-id="{{ $values->first()->attribute_value_id }}"
                                                                class="specification-hidden">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="product-details-action">
                                @if ($product->stock > 0)
                                    <form action="{{ route('add_cart') }}" method="POST" id="addToCartForm">
                                        @csrf
                                        <div class="details-action-wrapper">
                                            <div class="quantity-section">
                                                <label for="qty" class="quantity-label">Quantity:</label>
                                                <div class="product-details-quantity">
                                                    <button type="button" class="qty-btn qty-minus" data-action="minus">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" name="quantity" id="qty"
                                                        class="form-control qty-input" value="1" min="1"
                                                        max="{{ $product->stock }}" step="1" required>
                                                    <button type="button" class="qty-btn qty-plus" data-action="plus">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="action-section">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="selected_specifications" id="selectedSpecs"
                                                    value="">
                                                <button type="submit" class="btn btn-add-to-cart pulse-animation">
                                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="selected-specifications-summary" id="specsSummary">
                                        <h6 class="summary-title">
                                            <i class="fas fa-check-circle"></i> Selected Configuration
                                        </h6>
                                        <div class="specs-summary-content">
                                            <!-- Will be populated by JavaScript -->
                                        </div>
                                    </div>

                                    <div class="error-message" id="errorMessage">
                                        <i class="fas fa-exclamation-triangle"></i> Please select all required
                                        specifications before adding to cart.
                                    </div>

                                    <div class="success-message" id="successMessage">
                                        <i class="fas fa-check-circle"></i> Product added to cart successfully!
                                    </div>
                                @else
                                    <div class="out-of-stock">
                                        <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                        <h4 class="text-danger">This product is currently unavailable</h4>
                                        <p class="mb-3">You can browse other products in the same category</p>
                                        <a href="{{ route('view_product', ['department_id' => $product->department_id]) }}"
                                            class="btn btn-outline-primary">
                                            <i class="fas fa-search"></i> Browse {{ $product->department->name }} Products
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="product-details-tab">
                <ul class="nav nav-pills justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-desc-link" data-bs-toggle="tab" href="#product-desc-tab"
                            role="tab" aria-controls="product-desc-tab" aria-selected="true">
                            <i class="fas fa-file-text"></i> Description
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-info-link" data-bs-toggle="tab" href="#product-info-tab"
                            role="tab" aria-controls="product-info-tab" aria-selected="false">
                            <i class="fas fa-info-circle"></i> Product Info
                        </a>
                    </li>
                    @if ($product->attributeValues->isNotEmpty())
                        <li class="nav-item">
                            <a class="nav-link" id="product-specs-link" data-bs-toggle="tab" href="#product-specs-tab"
                                role="tab" aria-controls="product-specs-tab" aria-selected="false">
                                <i class="fas fa-list-ul"></i> All Specifications
                            </a>
                        </li>
                    @endif
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-desc-tab" role="tabpanel"
                        aria-labelledby="product-desc-link">
                        <div class="product-desc-content">
                            <h3>{{ $product->name }}</h3>
                            <p>{{ $product->description ?? 'No description available for this product.' }}</p>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="product-info-tab" role="tabpanel"
                        aria-labelledby="product-info-link">
                        <div class="product-desc-content">
                            <h3>Product Information</h3>
                            <ul class="list-unstyled mt-3">
                                <li><strong>Price:</strong> {{ number_format($product->price, 2) }} EGP</li>
                                <li><strong>Available Quantity:</strong> {{ $product->stock }} pieces</li>
                                <li><strong>Department:</strong> {{ $product->department->name }}</li>
                                @if ($product->discount)
                                    <li><strong>Discount:</strong> {{ $product->discount }}%</li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    @if ($product->attributeValues->isNotEmpty())
                        <div class="tab-pane fade" id="product-specs-tab" role="tabpanel"
                            aria-labelledby="product-specs-link">
                            <div class="product-desc-content">
                                <h3>All Available Specifications</h3>
                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered">
                                        <tbody>
                                            @foreach ($product->attributeValues->groupBy('attribute.name') as $attributeName => $values)
                                                <tr>
                                                    <td><strong>{{ $attributeName }}</strong></td>
                                                    <td>
                                                        @foreach ($values as $value)
                                                            {{ $value->attributeValue->value }}
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content bg-transparent border-0 shadow-none">
                <img id="modalImage" src="" class="img-fluid rounded" alt="Product Image">
                <button type="button" class="btn-close modal-close-btn" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .breadcrumb {
            background: none;
            padding: 1rem 0;
        }

        .product-gallery {
            position: relative;
        }

        .product-main-image {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem;
        }

        .product-main-image img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .product-main-image:hover img {
            transform: scale(1.05);
        }

        .product-label {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            z-index: 2;
        }

        .product-image-gallery {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .product-gallery-item {
            flex-shrink: 0;
            width: 80px;
            height: 80px;
            border-radius: 8px;
            overflow: hidden;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .product-gallery-item.active {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
        }

        .product-gallery-item:hover {
            border-color: #007bff;
            transform: translateY(-2px);
        }

        .product-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-details {
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .product-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .product-price {
            margin-bottom: 1.5rem;
        }

        .new-price {
            font-size: 2rem;
            font-weight: 700;
            color: #27ae60;
        }

        .old-price {
            font-size: 1.2rem;
            color: #95a5a6;
            text-decoration: line-through;
            margin-left: 15px;
        }

        .save-badge {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-left: 10px;
        }

        .product-content {
            margin-bottom: 2rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }

        .product-info-section {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .product-info-section h5 {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .status-badge {
            font-size: 1rem;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-available {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
        }

        .status-out {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .department-badge {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .department-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(108, 117, 125, 0.4);
            color: white;
            text-decoration: none;
        }

        /* Enhanced Specifications Styling */
        .product-attributes {
            margin-top: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .attributes-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            text-align: center;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .attributes-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-radius: 2px;
        }

        .specifications-selector {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .specification-group {
            margin-bottom: 1.5rem;
            padding: 1.2rem;
            background: white;
            border-radius: 10px;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .specification-group:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .specification-label {
            font-weight: 600;
            font-size: 1.1rem;
            color: #495057;
            margin-bottom: 1rem;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .specification-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
        }

        .specification-option {
            position: relative;
        }

        .specification-radio {
            opacity: 0;
            position: absolute;
            pointer-events: none;
        }

        .specification-option-label {
            display: block;
            padding: 12px 16px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
            color: #495057;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .specification-option-label::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s ease;
        }

        .specification-option-label:hover {
            border-color: #007bff;
            box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
            transform: translateY(-2px);
            background: rgba(0, 123, 255, 0.05);
        }

        .specification-option-label:hover::before {
            left: 100%;
        }

        .specification-radio:checked+.specification-option-label {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border-color: #007bff;
            color: white;
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }

        .specification-radio:checked+.specification-option-label::after {
            content: '✓';
            position: absolute;
            top: 5px;
            right: 8px;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .specification-single {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .single-spec-badge {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
        }

        .selected-specifications-summary {
            margin-top: 2rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
            border-radius: 10px;
            border: 1px solid #27ae60;
            border-left: 5px solid #27ae60;
        }

        .summary-title {
            color: #27ae60;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .specs-summary-content {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .spec-summary-item {
            background: white;
            padding: 8px 16px;
            border-radius: 20px;
            border: 1px solid #27ae60;
            color: #27ae60;
            font-weight: 500;
            font-size: 0.9rem;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
        }

        .product-details-action {
            margin-top: 2rem;
            padding: 1.5rem;
            background: white;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .details-action-wrapper {
            display: flex;
            align-items: end;
            gap: 2rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .quantity-section {
            flex: 1;
            min-width: 200px;
        }

        .quantity-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: block;
        }

        .product-details-quantity {
            display: flex;
            align-items: center;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            width: fit-content;
        }

        .qty-btn {
            background: #f8f9fa;
            border: none;
            width: 40px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #6c757d;
        }

        .qty-btn:hover {
            background: #007bff;
            color: white;
        }

        .qty-input {
            width: 80px;
            height: 45px;
            border: none;
            text-align: center;
            font-weight: 600;
            background: white;
        }

        .qty-input:focus {
            outline: none;
            box-shadow: none;
        }

        .action-section {
            flex: 1;
            min-width: 200px;
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 8px;
            padding: 12px 30px;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            width: 100%;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
        }

        .btn-add-to-cart::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-add-to-cart:hover::before {
            left: 100%;
        }

        .out-of-stock {
            text-align: center;
            padding: 2rem;
            background: #fff5f5;
            border: 2px dashed #e74c3c;
            border-radius: 12px;
            color: #e74c3c;
        }

        .error-message {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-top: 1rem;
            font-weight: 500;
            text-align: center;
            display: none;
        }

        .success-message {
            background: linear-gradient(135deg, #51cf66, #40c057);
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            margin-top: 1rem;
            font-weight: 500;
            text-align: center;
            display: none;
        }

        .product-details-tab {
            margin-top: 3rem;
        }

        .nav-pills .nav-link {
            border-radius: 25px;
            padding: 12px 25px;
            margin: 0 5px;
            font-weight: 500;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .nav-pills .nav-link:hover {
            background: rgba(0, 123, 255, 0.1);
            color: #007bff;
        }

        .tab-content {
            margin-top: 2rem;
            padding: 2rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .modal-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 768px) {
            .specification-options {
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            }

            .details-action-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: 1rem;
            }

            .quantity-section,
            .action-section {
                min-width: auto;
            }

            .product-details-quantity {
                justify-content: center;
            }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image gallery functionality
            const galleryItems = document.querySelectorAll('.product-gallery-item');
            const mainImage = document.getElementById('product-zoom');
            const mainImageContainer = document.querySelector('.product-main-image');

            galleryItems.forEach(function(item) {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all items
                    galleryItems.forEach(function(galleryItem) {
                        galleryItem.classList.remove('active');
                    });

                    // Add active class to clicked item
                    this.classList.add('active');

                    // Update main image
                    const newImageSrc = this.getAttribute('data-image');
                    const newZoomImage = this.getAttribute('data-zoom-image');

                    const newImg = new Image();
                    newImg.onload = function() {
                        mainImage.src = newImageSrc;
                        mainImage.setAttribute('data-zoom-image', newZoomImage);

                        // Add fade effect
                        mainImage.style.opacity = '0';
                        setTimeout(function() {
                            mainImage.style.opacity = '1';
                        }, 100);
                    };
                    newImg.src = newImageSrc;
                });
            });

            // Main image click to open modal
            if (mainImage) {
                mainImage.addEventListener('click', function() {
                    const currentSrc = this.src;
                    const modalImage = document.getElementById('modalImage');
                    modalImage.src = currentSrc;
                    const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
                    imageModal.show();
                });
            }

            // Quantity controls
            const qtyInput = document.getElementById('qty');
            const qtyBtns = document.querySelectorAll('.qty-btn');

            qtyBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const action = this.getAttribute('data-action');
                    let currentValue = parseInt(qtyInput.value);
                    const max = parseInt(qtyInput.getAttribute('max'));
                    const min = parseInt(qtyInput.getAttribute('min'));

                    if (action === 'plus' && currentValue < max) {
                        qtyInput.value = currentValue + 1;
                    } else if (action === 'minus' && currentValue > min) {
                        qtyInput.value = currentValue - 1;
                    }
                });
            });

            if (qtyInput) {
                qtyInput.addEventListener('change', function() {
                    const max = parseInt(this.getAttribute('max'));
                    const min = parseInt(this.getAttribute('min'));
                    let value = parseInt(this.value);

                    if (value > max) this.value = max;
                    if (value < min) this.value = min;
                    if (isNaN(value)) this.value = min;
                });
            }

            // Specifications functionality
            const specificationRadios = document.querySelectorAll('.specification-radio');
            const specificationHidden = document.querySelectorAll('.specification-hidden');
            const selectedSpecsInput = document.getElementById('selectedSpecs');
            const specsSummary = document.querySelector('.specs-summary-content');
            const errorMessage = document.getElementById('errorMessage');
            const successMessage = document.getElementById('successMessage');

            function updateSelectedSpecifications() {
                const selectedSpecs = {};
                const summaryItems = [];

                // Handle radio button specifications
                specificationRadios.forEach(function(radio) {
                    if (radio.checked) {
                        const attribute = radio.getAttribute('data-attribute');
                        const attributeId = radio.getAttribute('data-attribute-id');
                        const attributeValueId = radio.getAttribute('data-attribute-value-id');
                        const value = radio.value;

                        selectedSpecs[attributeId] = attributeValueId;
                        summaryItems.push(`<span class="spec-summary-item">${attribute}: ${value}</span>`);
                    }
                });

                // Handle hidden single specifications
                specificationHidden.forEach(function(hidden) {
                    const attribute = hidden.getAttribute('data-attribute');
                    const attributeId = hidden.getAttribute('data-attribute-id');
                    const attributeValueId = hidden.getAttribute('data-attribute-value-id');
                    const value = hidden.value;

                    selectedSpecs[attributeId] = attributeValueId;
                    summaryItems.push(`<span class="spec-summary-item">${attribute}: ${value}</span>`);
                });

                // Update hidden input with JSON data
                selectedSpecsInput.value = JSON.stringify(selectedSpecs);

                // Update summary display
                if (specsSummary) {
                    specsSummary.innerHTML = summaryItems.join('');
                }

                console.log('Selected specifications:', selectedSpecs);
            }

            // Listen for specification changes
            specificationRadios.forEach(function(radio) {
                radio.addEventListener('change', function() {
                    updateSelectedSpecifications();

                    // Hide error message if visible
                    if (errorMessage) {
                        errorMessage.style.display = 'none';
                    }
                });
            });

            // Initialize specifications on page load
            updateSelectedSpecifications();

            // Form submission handler
            const addToCartForm = document.getElementById('addToCartForm');
            if (addToCartForm) {
                addToCartForm.addEventListener('submit', function(e) {
                    const selectedSpecs = selectedSpecsInput.value;
                    const specsObj = JSON.parse(selectedSpecs || '{}');

                    // Check if all required specifications are selected
                    const requiredSpecsCount = specificationRadios.length > 0 ?
                        document.querySelectorAll('.specification-group').length : 0;
                    const selectedSpecsCount = Object.keys(specsObj).length;

                    if (requiredSpecsCount > 0 && selectedSpecsCount === 0) {
                        e.preventDefault();

                        if (errorMessage) {
                            errorMessage.style.display = 'block';
                            errorMessage.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                        } else {
                            alert('Please select all required specifications before adding to cart.');
                        }
                        return false;
                    }

                    // Show success message (optional)
                    if (successMessage) {
                        successMessage.style.display = 'block';
                        setTimeout(function() {
                            successMessage.style.display = 'none';
                        }, 3000);
                    }

                    console.log('Adding to cart with specifications:', selectedSpecs);
                    console.log('Quantity:', qtyInput.value);
                    console.log('Product ID:', document.querySelector('input[name="product_id"]').value);
                });
            }

            // Add fade-in animation to main elements
            setTimeout(function() {
                document.querySelector('.product-gallery').classList.add('fade-in');
                document.querySelector('.product-details').classList.add('fade-in');
            }, 100);
        });
    </script>
@endsection
