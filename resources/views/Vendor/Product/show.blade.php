@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4 text-center text-dark">Product Details</h3>

    {{-- ✅ الصورة الرئيسية --}}
    @if($product->mainImage)
        <div class="text-center mb-4">
            <img src="{{ asset('images/products/' . $product->mainImage->image) }}" alt="Main Image" class="img-fluid" style="max-height: 300px;">
        </div>
    @endif

    {{-- ✅ صور إضافية --}}
    @if($product->images->count())
        <div class="row mb-4">
            @foreach($product->images as $image)
                <div class="col-md-3 mb-3">
                    <img src="{{ asset('images/products/' . $image->image) }}" alt="Product Image" class="img-thumbnail">
                </div>
            @endforeach
        </div>
    @endif

    {{-- ✅ معلومات المنتج --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3 text-dark fw-bold">Product Info</h5>
            <p><strong>Name:</strong> {{ $product->name }}</p>
            <p><strong>Description:</strong> {{ $product->description }}</p>
            <p><strong>Price:</strong> {{ $product->price }} EG</p>
            <p><strong>Discount:</strong> {{ $product->discount }} %</p>
            <p><strong>Stock:</strong> {{ $product->stock }}</p>
        </div>
    </div>

    {{-- ✅ خصائص المنتج --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <h5 class="mb-3 text-dark fw-bold">Attributes</h5>

            @php
                $groupedAttributes = [];
                foreach ($product->attributeValues as $value) {
                    $attrName = $value->attribute->name;
                    $attrValue = $value->attributeValue->value;
                    if (!isset($groupedAttributes[$attrName])) {
                        $groupedAttributes[$attrName] = [];
                    }
                    if (!in_array($attrValue, $groupedAttributes[$attrName])) {
                        $groupedAttributes[$attrName][] = $attrValue;
                    }
                }
            @endphp

            @forelse ($groupedAttributes as $attrName => $values)
                <p class="mb-2">
                    <strong>{{ $attrName }}:</strong> {{ implode(', ', $values) }}
                </p>
            @empty
                <p>No attributes found for this product.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
