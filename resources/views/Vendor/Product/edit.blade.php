@extends('layouts.app')
@section('title', 'Edit Product')
@section('content')
    <div class="container">
        <h3 class="fw-bold mb-4 text-dark text-center">Edit Product</h3>
        <div class="card shadow border-0">
            <div class="card-body">

                @if (session('error'))
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

                <form action="{{ route('product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Row 1 --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-semibold">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                value="{{ old('name', $product->name) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label fw-semibold">Stock Quantity</label>
                            <input type="number" name="stock" id="stock" class="form-control" required
                                value="{{ old('stock', $product->stock) }}">
                        </div>
                    </div>

                    {{-- Row 2 --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="price" class="form-label fw-semibold">Price (EG)</label>
                            <input type="number" step="0.01" name="price" id="price" class="form-control" required
                                value="{{ old('price', $product->price) }}">
                        </div>
                        <div class="col-md-6">
                            <label for="discount" class="form-label fw-semibold">Discount (%)</label>
                            <input type="number" step="0.01" name="discount" id="discount" class="form-control"
                                value="{{ old('discount', $product->discount) }}">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>

                    {{-- Upload New Images --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-semibold">Upload New Images <small class="text-muted">(optional)</small></label>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addImageBtn">
                                <i class="fas fa-plus me-1"></i> Add Image
                            </button>
                        </div>
                        <div id="imagesContainer">
                            <div class="image-input mb-2">
                                <input type="file" name="images[]" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>

                    {{-- Product Attributes --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-semibold">Product Attributes</label>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="addAttributeBtn">
                                <i class="fas fa-plus me-1"></i> Add Attribute
                            </button>
                        </div>
                        <div id="attributesContainer"></div>
                    </div>

                    {{-- Buttons --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('product') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @php
        $groupedAttributes = [];
        foreach ($product->attributeValues as $attrValue) {
            $attrId = $attrValue->attribute->id;
            if (!isset($groupedAttributes[$attrId])) {
                $groupedAttributes[$attrId] = [
                    'attribute_id' => $attrValue->attribute->id,
                    'value_ids' => [],
                ];
            }
            $groupedAttributes[$attrId]['value_ids'][] = $attrValue->attribute_value_id;
        }
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let attributeCounter = 0;
            const attributesData = @json($attributes);
            const productAttributes = @json(array_values($groupedAttributes));

            const addAttributeBtn = document.getElementById('addAttributeBtn');
            const attributesContainer = document.getElementById('attributesContainer');
            const addImageBtn = document.getElementById('addImageBtn');
            const imagesContainer = document.getElementById('imagesContainer');

            addImageBtn.addEventListener('click', function() {
                const newInput = document.createElement('div');
                newInput.classList.add('image-input', 'mb-2');
                newInput.innerHTML = `
                    <div class="d-flex align-items-center">
                        <input type="file" name="images[]" class="form-control me-2" accept="image/*">
                        <button type="button" class="btn btn-sm btn-danger remove-image">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                imagesContainer.appendChild(newInput);
                newInput.querySelector('.remove-image').addEventListener('click', function() {
                    newInput.remove();
                });
            });

            addAttributeBtn.addEventListener('click', function() {
                const selectedIds = getSelectedAttributeIds();
                const available = attributesData.filter(attr => !selectedIds.includes(attr.id.toString()));
                if (available.length === 0) {
                    alert('All attributes have been added.');
                    return;
                }
                addAttributeRow();
            });

            function getSelectedAttributeIds(excludeCounter = null) {
                return Array.from(document.querySelectorAll('.attribute-select'))
                    .filter(select => excludeCounter === null || select.dataset.counter !== excludeCounter.toString())
                    .map(select => select.value)
                    .filter(val => val !== "");
            }

            function addAttributeRow(selectedAttributeId = null, selectedValueIds = []) {
                attributeCounter++;
                const attributeRow = document.createElement('div');
                attributeRow.className = 'attribute-row mb-3 p-3 border rounded bg-light';
                attributeRow.setAttribute('data-attribute-id', attributeCounter);

                attributeRow.innerHTML = `
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Attribute</label>
                            <select name="attributes[${attributeCounter}][attribute_id]" class="form-select attribute-select" data-counter="${attributeCounter}" required>
                                <option value="">Select Attribute</option>
                                ${attributesData.map(attr => {
                                    const isSelected = selectedAttributeId == attr.id ? 'selected' : '';
                                    const isDisabled = getSelectedAttributeIds().includes(attr.id.toString()) && !isSelected ? 'disabled' : '';
                                    return `<option value="${attr.id}" ${isSelected} ${isDisabled}>${attr.name}</option>`;
                                }).join('')}
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Values</label>
                            <select name="attributes[${attributeCounter}][value_ids][]" class="form-select value-select" data-counter="${attributeCounter}" ${!selectedAttributeId ? 'disabled' : ''} multiple required>
                                <option value="">Select Values</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger btn-sm remove-attribute" data-counter="${attributeCounter}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                attributesContainer.appendChild(attributeRow);
                setupAttributeListeners(attributeCounter);

                if (selectedAttributeId) {
                    const attributeSelect = document.querySelector(`select[name="attributes[${attributeCounter}][attribute_id]"]`);
                    const valueSelect = document.querySelector(`select[name="attributes[${attributeCounter}][value_ids][]"]`);
                    updateValueOptions(attributeSelect, valueSelect, selectedAttributeId, selectedValueIds);
                }
            }

            function updateValueOptions(attributeSelect, valueSelect, attributeId, selectedValueIds = []) {
                valueSelect.innerHTML = '';
                const selectedAttribute = attributesData.find(attr => attr.id == attributeId);
                if (selectedAttribute && selectedAttribute.values) {
                    selectedAttribute.values.forEach(value => {
                        const option = document.createElement('option');
                        option.value = value.id;
                        option.textContent = value.value;
                        if (selectedValueIds.includes(value.id)) {
                            option.selected = true;
                        }
                        valueSelect.appendChild(option);
                    });
                    valueSelect.disabled = false;
                } else {
                    valueSelect.disabled = true;
                }
            }

            function setupAttributeListeners(counter) {
                const attributeSelect = document.querySelector(`select[name="attributes[${counter}][attribute_id]"]`);
                const valueSelect = document.querySelector(`select[name="attributes[${counter}][value_ids][]"]`);
                const removeBtn = document.querySelector(`button[data-counter="${counter}"]`);

                attributeSelect.addEventListener('change', function() {
                    const selectedId = this.value;
                    const othersSelected = getSelectedAttributeIds(counter);
                    if (othersSelected.includes(selectedId)) {
                        alert("This attribute has already been selected.");
                        this.value = "";
                        valueSelect.innerHTML = '';
                        valueSelect.disabled = true;
                        return;
                    }

                    updateValueOptions(attributeSelect, valueSelect, selectedId);
                    refreshAllAttributeOptions();
                });

                removeBtn.addEventListener('click', function() {
                    const allRows = document.querySelectorAll('.attribute-row');
                    if (allRows.length <= 1) {
                        alert("At least one attribute is required.");
                        return;
                    }

                    const attributeRow = document.querySelector(`div[data-attribute-id="${counter}"]`);
                    if (attributeRow) {
                        attributeRow.remove();
                        refreshAllAttributeOptions();
                    }
                });
            }

            function refreshAllAttributeOptions() {
                document.querySelectorAll('.attribute-select').forEach(select => {
                    const currentVal = select.value;
                    const counter = select.dataset.counter;
                    const options = Array.from(select.options);
                    options.forEach(option => {
                        if (option.value === "" || option.value === currentVal) {
                            option.disabled = false;
                        } else {
                            option.disabled = getSelectedAttributeIds(counter).includes(option.value);
                        }
                    });
                });
            }

            productAttributes.forEach(attr => {
                addAttributeRow(attr.attribute_id, attr.value_ids);
            });
        });
    </script>

    <style>
        .attribute-row {
            position: relative;
            transition: all 0.3s ease;
        }

        .attribute-row:hover {
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .remove-attribute,
        .remove-image {
            height: 38px;
        }

        .value-select:disabled {
            background-color: #e9ecef;
            opacity: 0.65;
        }

        #addAttributeBtn,
        #addImageBtn {
            transition: all 0.3s ease;
        }

        #addAttributeBtn:hover,
        #addImageBtn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
