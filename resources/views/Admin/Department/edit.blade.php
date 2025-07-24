@extends("layouts.app")

@section("title", "Edit Department")

@section("content")
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-4 text-center">Edit Department</h4>

            {{-- Error Messages --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('department.update', $department->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Department Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Department Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', $department->name) }}" required>
                </div>

                {{-- Submit --}}
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary">Update Department</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
