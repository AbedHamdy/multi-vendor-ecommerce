@extends("layouts.app")

@section("title", "Package Details")

@section("content")
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0 text-dark">Package Details</h3>
        <a href="{{ route('package') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Package Card --}}
    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <h4 class="fw-bold mb-3">
                Package :
                <span class="px-3 py-1 bg-info text-dark rounded">
                    {{ $package->name }}
                </span>
            </h4>

            <div class="mb-3">
                <strong>Status:</strong>
                @if($package->status == 'active')
                    <span class="badge bg-success">Active</span>
                @else
                    <span class="badge bg-danger">Inactive</span>
                @endif
            </div>

            <div class="mb-3">
                <strong>Total Features:</strong>
                {{ $package->features->count() }}
            </div>

            <div class="text-end">
                <a href="{{ route('package.edit', $package->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
            </div>
        </div>
    </div>

    {{-- Features Table --}}
    <div class="card shadow border-0">
        <div class="card-body table-responsive">
            <h5 class="fw-bold mb-3">Features</h5>
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Feature Title</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($package->features as $index => $feature)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $feature->title }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted">No features added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
