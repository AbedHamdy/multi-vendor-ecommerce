@extends('layouts.app')
@section('title', 'Edit Attributes')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Edit Attributes for Department: {{ $department->name }}</h3>

    <form action="{{ route('attribute.update', $department->id) }}" method="POST" id="attribute-form">
        @csrf
        @method('PUT')

        <div id="attributes-container">
            @foreach ($department->attributes as $index => $attribute)
                <div class="card mb-4 shadow-sm border-0 attribute-card" data-index="{{ $index }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Attribute #{{ $index + 1 }}</h5>
                            <button type="button" class="btn btn-danger btn-sm remove-attribute">Remove</button>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attribute Name</label>
                            <input type="text" name="attributes[{{ $index }}][name]" class="form-control" value="{{ $attribute->name }}">
                        </div>

                        <div class="multi-text-container">
                            <label class="form-label">Values</label>
                            @foreach ($attribute->values as $value)
                                <div class="multi-text-item mb-2">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="attributes[{{ $index }}][multi_text_values][]" class="form-control" value="{{ $value->value }}">
                                        <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-multi-text">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm add-multi-text">Add Value</button>
                    </div>
                </div>
            @endforeach
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
@endsection

@push('scripts')
<script>
    const attributesContainer = document.getElementById('attributes-container');

    function createAttributeCard(index) {
        const card = document.createElement('div');
        card.className = 'card mb-4 shadow-sm border-0 attribute-card';
        card.dataset.index = index;

        card.innerHTML = `
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Attribute #${index + 1}</h5>
                    <button type="button" class="btn btn-danger btn-sm remove-attribute">Remove</button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Attribute Name</label>
                    <input type="text" name="attributes[${index}][name]" class="form-control">
                </div>

                <div class="multi-text-container mb-3">
                    <label class="form-label">Values</label>
                    <div class="multi-text-item mb-2">
                        <div class="d-flex align-items-center">
                            <input type="text" name="attributes[${index}][multi_text_values][]" class="form-control" placeholder="Enter value">
                            <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-multi-text">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm add-multi-text">Add Value</button>
            </div>
        `;
        return card;
    }

    document.getElementById('add-attribute').addEventListener('click', function () {
        const currentCount = document.querySelectorAll('.attribute-card').length;
        const newCard = createAttributeCard(currentCount);
        attributesContainer.appendChild(newCard);
    });

    // حذف خاصية
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-attribute')) {
            const totalAttributes = document.querySelectorAll('.attribute-card').length;

            if (totalAttributes <= 1) {
                alert("Please ensure the department has at least one attribute.");
                return;
            }

            e.target.closest('.attribute-card').remove();

            document.querySelectorAll('.attribute-card').forEach((card, index) => {
                card.dataset.index = index;
                card.querySelector('h5').innerText = `Attribute #${index + 1}`;
                card.querySelector('input[name^="attributes"]').name = `attributes[${index}][name]`;

                const valueInputs = card.querySelectorAll('.multi-text-item input');
                valueInputs.forEach(input => {
                    input.name = `attributes[${index}][multi_text_values][]`;
                });
            });
        }
    });

    // إضافة قيمة جديدة
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-multi-text')) {
            const card = e.target.closest('.attribute-card');
            const index = Array.from(document.querySelectorAll('.attribute-card')).indexOf(card);
            const container = card.querySelector('.multi-text-container');
            const div = document.createElement('div');
            div.className = 'multi-text-item mb-2';
            div.innerHTML = `
                <div class="d-flex align-items-center">
                    <input type="text" name="attributes[${index}][multi_text_values][]" class="form-control" placeholder="Enter value">
                    <button type="button" class="btn btn-outline-danger btn-sm ms-2 remove-multi-text">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            container.appendChild(div);
        }
    });

    // حذف قيمة من خاصية
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-multi-text') || e.target.closest('.remove-multi-text')) {
            const container = e.target.closest('.multi-text-container');
            const values = container.querySelectorAll('.multi-text-item');

            if (values.length <= 1) {
                alert("Please ensure each attribute has at least one value.");
                return;
            }

            e.target.closest('.multi-text-item').remove();
        }
    });

    // التحقق قبل الإرسال
    document.getElementById('attribute-form').addEventListener('submit', function (e) {
        const attributeCards = document.querySelectorAll('.attribute-card');

        if (attributeCards.length === 0) {
            alert('Please add at least one attribute.');
            e.preventDefault();
            return;
        }

        let valid = true;

        attributeCards.forEach((card, index) => {
            const nameInput = card.querySelector(`input[name="attributes[${index}][name]"]`);
            const valueInputs = card.querySelectorAll(`input[name="attributes[${index}][multi_text_values][]"]`);

            if (!nameInput || nameInput.value.trim() === '') {
                alert(`Please enter a name for attribute number ${index + 1}`);
                valid = false;
                e.preventDefault();
                return;
            }

            let hasValue = false;
            valueInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    hasValue = true;
                }
            });

            if (!hasValue) {
                alert(`The attribute number ${index + 1} must contain at least one value.`);
                valid = false;
                e.preventDefault();
                return;
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
</script>
@endpush
