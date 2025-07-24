{{-- resources/views/Client/auth/register.blade.php --}}
@extends('Client.layouts.app')

@section('title', 'Register')

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

                <h2 class="text-center mb-4">Register</h2>
                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="register-name">Name *</label>
                        <input type="text" class="form-control" name="register-name" value="{{ old('register-name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="register-email">Email *</label>
                        <input type="email" class="form-control" name="register-email" value="{{ old('register-email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="register-password">Password *</label>
                        <input type="password" class="form-control" name="register-password" value="{{ old('register-password') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="register-password_confirmation">Confirm Password *</label>
                        <input type="password" class="form-control" name="register-password_confirmation" required>
                    </div>

                    <div class="form-footer text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm login-btn-custom">
                            <span class="fw-bold">SIGN UP</span>
                            <i class="icon-long-arrow-right ms-2"></i>
                        </button>
                    </div>

                    <p class="text-center mt-3">
                        Already have an account?
                        <a href="{{ route('login') }}">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
