@extends("layouts.app")

@section("title", "Vendors")

@section("content")
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0 text-dark">Vendors List</h3>
        {{-- <a href="{{ route('vendor.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add Vendor
        </a> --}}
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Vendor Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Package</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $index => $vendor)
                        <tr>
                            <td>{{ $vendors->firstItem() + $index }}</td>
                            <td class="fw-semibold">{{ $vendor->name }}</td>
                            <td>{{ $vendor->email ?? 'N/A' }}</td>
                            <td>{{ $vendor->department->name }}</td>
                            <td>{{ $vendor->package->name }}</td>
                            <td>
                                @if($vendor->status === 'active')
                                    <span class="badge bg-success px-3 py-2">Active</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">Inactive</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('vendor.show', $vendor->id) }}">
                                                <i class="fas fa-eye me-2 text-info"></i> View
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('vendor.edit', $vendor->id) }}">
                                                <i class="fas fa-edit me-2 text-warning"></i> Change Status
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('vendor.delete', $vendor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this vendor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="fas fa-trash me-2"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No vendors available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $vendors->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
