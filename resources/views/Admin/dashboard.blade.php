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

        {{-- Total Delivered Orders --}}
        <div class="card">
            <div class="card-header">
                <div class="card-info">
                    <h3>Delivered Orders</h3>
                    <h2>{{ $deliveredOrdersCount }}</h2>
                </div>
                <div class="card-icon blue">
                    <i class="fas fa-truck"></i>
                </div>
            </div>
        </div>

        <div class="card shadow-sm rounded-lg border-0">
            <div class="card-header d-flex justify-content-between align-items-center bg-light p-3 rounded-top">
                <div class="card-info">
                    <h5 class="mb-1 text-muted">Sales Revenue</h5>
                    <h2 class="mb-0 text-primary fw-bold">{{ $totalCompletedOrdersPrice }} EGP</h2>
                </div>
                <div class="card-icon bg-primary text-white d-flex align-items-center justify-content-center rounded-circle" style="width:50px; height:50px;">
                    <i class="fas fa-sack-dollar fa-"></i>
                </div>
            </div>
        </div>


    </div>
@endsection
lg
