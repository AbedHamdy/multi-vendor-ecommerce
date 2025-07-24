@extends('layouts.app')
@section('title', 'Add Attribute')

@section('content')
    <div class="container py-4">
        <h3 class="mb-4">Add Attribute to Department: {{ $department->name }}</h3>

        <div class="card shadow border-0">
            <div class="card-body">
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
                <form action="{{ route('attribute.store') }}" method="POST" id="attribute-form">
                    @csrf
                    <input type="hidden" name="department_id" value="{{ $department->id }}">

                    <div id="attributes-container">
                        <!-- First Attribute -->
                        <div class="attribute-group border rounded p-3 mb-4">
                            <div class="mb-3">
                                <label class="form-label">Attribute Name</label>
                                <input type="text" name="attributes[0][name]" class="form-control"
                                    placeholder="Enter attribute name">
                            </div>

                            <div class="multi-text-container">
                                <label class="form-label">Values</label>
                                <div class="multi-text-item mb-2">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="attributes[0][multi_text_values][]" class="form-control"
                                            placeholder="Enter value">
                                        <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-multi-text"
                                            disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-multi-text">Add
                                More</button>
                        </div>
                    </div>

                    <div class="text-start mb-4">
                        <button type="button" class="btn btn-primary" id="add-attribute">
                            <i class="fas fa-plus"></i> Add Another Attribute
                        </button>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save All Attributes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let attributeIndex = 1;

        document.getElementById('add-attribute').addEventListener('click', function() {
            const container = document.getElementById('attributes-container');
            const newAttributeHTML = createAttributeHTML(attributeIndex);
            container.insertAdjacentHTML('beforeend', newAttributeHTML);
            attributeIndex++;
            updateMultiTextButtons();
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.add-multi-text')) {
                const group = e.target.closest('.attribute-group');
                const multiTextContainer = group.querySelector('.multi-text-container');
                const inputGroup = document.createElement('div');
                inputGroup.classList.add('multi-text-item', 'mb-2');
                inputGroup.innerHTML = `
                <div class="d-flex align-items-center">
                    <input type="text" name="${getMultiTextName(group)}" class="form-control" placeholder="Enter value">
                    <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-multi-text">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
                multiTextContainer.appendChild(inputGroup);
                updateMultiTextButtons();
            }

            if (e.target.closest('.remove-multi-text')) {
                const item = e.target.closest('.multi-text-item');
                const container = item.parentElement;
                if (container.querySelectorAll('.multi-text-item').length > 1) {
                    item.remove();
                    updateMultiTextButtons();
                }
            }
        });

        function createAttributeHTML(index) {
            return `
        <div class="attribute-group border rounded p-3 mb-4">
            <div class="mb-3">
                <label class="form-label">Attribute Name</label>
                <input type="text" name="attributes[${index}][name]" class="form-control" placeholder="Enter attribute name">
            </div>

            <div class="multi-text-container">
                <label class="form-label">Values</label>
                <div class="multi-text-item mb-2">
                    <div class="d-flex align-items-center">
                        <input type="text" name="attributes[${index}][multi_text_values][]" class="form-control" placeholder="Enter value">
                        <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-multi-text" disabled>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-outline-primary btn-sm mt-2 add-multi-text">Add More</button>
        </div>
        `;
        }

        function getMultiTextName(group) {
            const indexMatch = group.querySelector('input[name^="attributes["]').name.match(/attributes\[(\d+)\]/);
            if (!indexMatch) return '';
            return `attributes[${indexMatch[1]}][multi_text_values][]`;
        }

        function updateMultiTextButtons() {
            document.querySelectorAll('.multi-text-container').forEach(container => {
                const removeButtons = container.querySelectorAll('.remove-multi-text');
                const totalItems = container.querySelectorAll('.multi-text-item').length;
                removeButtons.forEach((btn) => {
                    btn.disabled = totalItems <= 1;
                });
            });
        }
    </script>
@endpush
