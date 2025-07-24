@extends("layouts.app")

@section("title" , "Dashboard")

@section("content")
    <div class="dashboard-cards">
        {{-- Total Packages --}}
        <div class="card">
            <div class="card-header">
                <div class="card-info">
                    <h3>Total Packages</h3>
                    <h2>{{ $packageCount }}</h2>
                </div>
                <div class="card-icon blue">
                    <i class="fas fa-box-open"></i>
                </div>
            </div>
        </div>

        {{-- Total Departments --}}
        <div class="card">
            <div class="card-header">
                <div class="card-info">
                    <h3>Total Departments</h3>
                    <h2>{{ $departmentCount }}</h2>
                </div>
                <div class="card-icon orange">
                    <i class="fas fa-building"></i>
                </div>
            </div>
        </div>

        {{-- Total Vendors --}}
        <div class="card">
            <div class="card-header">
                <div class="card-info">
                    <h3>Total Vendors</h3>
                    <h2>{{ $vendorCount }}</h2>
                </div>
                <div class="card-icon green">
                    <i class="fas fa-store"></i>
                </div>
            </div>
        </div>

        {{-- Total Users --}}
        <div class="card">
            <div class="card-header">
                <div class="card-info">
                    <h3>Total Users</h3>
                    <h2>{{ $userCount }}</h2>
                </div>
                <div class="card-icon purple">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
