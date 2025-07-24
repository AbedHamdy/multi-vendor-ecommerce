@extends('Client.layouts.app')

@section('title', 'Package : ' . $package->name)

@section('content')
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Package : {{ $package->name }}</li>
            </ol>
        </div>
    </nav>

    <div class="container my-5">
        <div class="card shadow border-0">
            <div class="card-body">
                <h2 class="fw-bold text-center">
                    Package :
                    <span class="bg-primary text-white px-3 py-1 rounded">
                        {{ $package->name }}
                    </span>
                </h2>

                <p class="text-center mt-3">
                    Price:
                    <span class="bg-success text-white px-3 py-1 rounded">
                        ${{ number_format($package->price, 2) }}
                    </span>
                </p>
                <hr>
                <h5 class="mb-3  text-center">Features:</h5>
                @if ($package->features->count())
                    <ul class="list-group list-group-flush text-center fs-5">
                        @foreach ($package->features as $feature)
                            <li class="list-group-item">
                                <i class="fas fa-check text-success me-2"></i> {{ $feature->title }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No features listed for this package.</p>
                @endif
                <div class="text-center mt-4">
                    <a href="{{ route("package.checkout" , $package->id) }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-shopping-cart me-2"></i> Subscribe Now
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection
