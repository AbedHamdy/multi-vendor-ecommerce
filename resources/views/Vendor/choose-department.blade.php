<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Choose Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .department-card {
            border: 2px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: 0.3s;
            height: 100%;
            text-align: center;
        }

        .department-card:hover {
            background-color: #f9f9f9;
            border-color: #007bff;
        }

        .department-card input[type="radio"] {
            display: none;
        }

        .department-card.checked {
            background-color: #e6f2ff;
            border-color: #007bff;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4 text-center">Select Your Department</h2>

    <form action="{{ route('department.submit') }}" method="POST">
        @csrf
        <div class="row">
            @foreach ($departments as $department)
                <div class="col-md-4 mb-3">
                    <label class="department-card d-block" onclick="selectOnlyOne(this)">
                        <input type="radio" name="department_id" value="{{ $department->id }}">
                        <strong>{{ $department->name }}</strong>
                    </label>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Confirm</button>
        </div>
    </form>
</div>

<script>
    function selectOnlyOne(selectedCard) {
        document.querySelectorAll('.department-card').forEach(card => {
            card.classList.remove('checked');
            card.querySelector('input[type="radio"]').checked = false;
        });

        selectedCard.classList.add('checked');
        selectedCard.querySelector('input[type="radio"]').checked = true;
    }
</script>
</body>
</html>
