@extends('layouts.app')

@section('title', 'Department Details')

@section('content')
    <div class="container py-4">
        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Header --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="mb-4">
                    <h3 class="fw-bold text-dark">Department: {{ $department->name }}</h3>
                    <p class="text-muted mb-0">Vendors Count: {{ $department->vendors_count }}</p>
                </div>

                {{-- Attributes --}}
                <div class="mb-3 border-top pt-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Attributes for this Department</h5>
                        @if ($department->attributes->count())
                            <a href="{{ route('attribute.edit', $department->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i> Edit Attributes
                            </a>
                        @else
                            <a href="{{ route('attribute.create', $department->id) }}"
                                class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-plus me-1"></i> Add Attributes
                            </a>
                        @endif
                    </div>

                    @if ($department->attributes->count())
                        <div class="row">
                            @foreach ($department->attributes as $attribute)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body d-flex flex-column">
                                            <h6 class="fw-bold text-primary">{{ $attribute->name }}</h6>
                                            <p class="text-muted mb-2">Type: {{ ucfirst($attribute->type ?? 'text') }}</p>

                                            {{-- Values --}}
                                            @if ($attribute->values->count())
                                                <ul class="list-unstyled mb-3">
                                                    @foreach ($attribute->values as $value)
                                                        <li>
                                                            <i class="fas fa-circle text-muted small me-1"></i>
                                                            {{ $value->value }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted small mb-3">No values found.</p>
                                            @endif

                                            {{-- Delete Attribute Button --}}
                                            <form
                                                action="{{ route('attribute.destroy', [$department->id, $attribute->id]) }}"method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this attribute?');"
                                                class="mt-auto">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete Attribute
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No attributes found for this department.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Vendors Table --}}
        <div class="card shadow border-0">
            <div class="card-body table-responsive">
                <h5 class="mb-3">Vendors in this Department</h5>
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($department->vendors as $index => $vendor)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $vendor->name }}</td>
                                <td>{{ $vendor->email }}</td>
                                <td class="text-center">
                                    <form action="" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No vendors in this department.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
