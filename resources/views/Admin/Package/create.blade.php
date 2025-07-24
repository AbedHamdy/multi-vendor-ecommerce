@extends("layouts.app")

@section("title", "Create Package")

@section("content")
<div class="container py-4">
    <div class="card shadow border-0">
        <div class="card-body">
            <h4 class="fw-bold mb-4 text-center">Add New Package</h4>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('package.store') }}" method="POST">
                @csrf

                {{-- Package Name --}}
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label" for="name">Package Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter name package" required>
                    </div>

                    <div class="mb-3 col-6">
                        <label class="form-label" for="price">Package Price</label>
                        <input type="number" id="price" name="price" class="form-control" placeholder="Enter price package" required>
                    </div>
                </div>

                {{-- Features --}}
                <label class="form-label" for="feet">Features</label>
                <div id="features-wrapper">
                    <div class="input-group mb-2 feature-item">
                        <input type="text" id="feet" name="features[]" class="form-control" placeholder="Enter feature" required>
                        <button type="button" class="btn btn-danger remove-feature">Remove</button>
                    </div>
                </div>

                <button type="button" id="add-feature" class="btn btn-sm btn-primary mb-3">+ Add Feature</button>

                {{-- Submit --}}
                <div class="mt-3 text-center">
                    <button type="submit" class="btn btn-primary">Save Package</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('add-feature').addEventListener('click', function () {
        const wrapper = document.getElementById('features-wrapper');
        const feature = document.createElement('div');
        feature.classList.add('input-group', 'mb-2', 'feature-item');
        feature.innerHTML = `
            <input type="text" id="feet" name="features[]" class="form-control" placeholder="Enter feature" required>
            <button type="button" class="btn btn-danger remove-feature">Remove</button>
        `;
        wrapper.appendChild(feature);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-feature')) {
            const features = document.querySelectorAll('#features-wrapper .feature-item');
            if (features.length > 1) {
                e.target.parentElement.remove();
            } else {
                alert("At least one feature is required.");
            }
        }
    });
</script>
@endpush
