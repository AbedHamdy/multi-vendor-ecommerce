@extends("layouts.app")

@section("title", "Create Department")

@section("content")
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-4 text-center">Add New Department</h4>

            {{-- Session Error --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('department.store') }}" method="POST">
                @csrf

                {{-- Department Name --}}
                <div class="mb-3">
                    <label class="form-label" for="name">Department Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter department name" required>
                </div>

                {{-- Submit --}}
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
