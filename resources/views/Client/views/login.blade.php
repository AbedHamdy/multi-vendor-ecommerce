{{-- resources/views/Client/auth/login.blade.php --}}
@extends('Client.layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-page bg-image pt-8 pb-8 pt-md-12 pb-md-12 pt-lg-17 pb-lg-17"
    style="background-image: url('/assets/images/backgrounds/login-bg.jpg')">
    <div class="container">
        <div class="form-box">
            <div class="form-tab">

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-primary">{{ session('success') }}</div>
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

                <h2 class="text-center mb-4">Sign In</h2>
                <form action="{{ route('check_credentials') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="singin-email">Email *</label>
                        <input type="email" class="form-control" name="singin-email" value="{{ old('singin-email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="singin-password">Password *</label>
                        <input type="password" class="form-control" name="singin-password" value="{{ old('singin-password') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="login-role">Role *</label>
                        <select class="form-control" name="login-role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="vendor">Vendor</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div class="form-footer text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm login-btn-custom">
                            <span class="fw-bold">LOG IN</span>
                            <i class="icon-long-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <p class="text-center mt-3">
                        Don't have an account?
                        <a href="{{ route('register_page') }}">Register here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
