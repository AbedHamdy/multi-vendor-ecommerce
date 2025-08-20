<aside class="sidebar" id="sidebar">
    <ul class="sidebar-menu">
        @if(auth('admin')->check())
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('package') }}" class="{{ request()->routeIs('package*') ? 'active' : '' }}">
                    <i class="fas fa-box"></i> Packages
                </a>
            </li>
            <li>
                <a href="{{ route('department') }}" class="{{ request()->routeIs('department*') ? 'active' : '' }}">
                    <i class="fas fa-layer-group"></i> Departments
                </a>
            </li>
            <li>
                <a href="{{ route('vendor') }}" class="{{ request()->routeIs('vendor*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Vendor
                </a>
            </li>
            <li>
                <a href="{{ route("admin.all_orders") }}" class="{{ request()->routeIs('all_orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> All Orders
                </a>
            </li>
            <li>
                <form method="POST" action="{{ route('logout_admin') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="btn w-100 text-start {{ request()->routeIs('logout') ? 'active' : '' }}"
                        style="background-color: transparent; border: none; padding: 10px 15px; color: #000; display: flex; align-items: center;">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        @endif

        @if(auth('vendor')->check())
            <li>
                <a href="{{ route('dashboard_vendor') }}" class="{{ request()->routeIs('dashboard_vendor') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('product') }}" class="{{ request()->routeIs('product*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-bag"></i> Products
                </a>
            </li>
            <li>
                <a href="{{ route('coupon') }}" class="{{ request()->routeIs('coupon*') ? 'active' : '' }}">
                    <i class="fas fa-ticket"></i> Coupons
                </a>
            </li>
            <li>
                <a href="{{ route("all_orders") }}" class="{{ request()->routeIs('all_orders*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-cart"></i> All Orders
                </a>
            </li>

            <li>
                <form method="POST" action="{{ route('logout_vendor') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="btn w-100 text-start {{ request()->routeIs('logout_vendor') ? 'active' : '' }}"
                        style="background-color: transparent; border: none; padding: 10px 15px; color: #000; display: flex; align-items: center;">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        @endif
    </ul>
</aside>
