<nav class="navbar">
    <div class="navbar-left">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars" id="menu-icon"></i>
        </button>
        <div class="logo">Dashboard</div>
    </div>
    <div class="navbar-right">
        {{-- <div class="search-box">
            <input type="text" placeholder="Search...">
            <i class="fas fa-search"></i>
        </div> --}}
        <div class="navbar-icons">

            @php
                if(auth()->guard('vendor')->check())
                {
                    $user = auth()->guard('vendor')->user();
                    $notificationsRoute = route('vendor.unread_notifications');
                } elseif(auth()->guard('admin')->check())
                {
                    $user = auth()->guard('admin')->user();
                    $notificationsRoute = route('admin.unread_notifications');
                }
                else
                {
                    $user = null;
                    $notificationsRoute = '#';
                }
                $unreadCount = $user ? $user->unreadNotifications->count() : 0;
            @endphp

            <a href="{{ $notificationsRoute }}" class="position-relative btn btn-light">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>

            <a href="{{ auth()->guard('admin')->check()
                ? route('admin.change_password')
                : (auth()->guard('vendor')->check()
                    ? route('vendor.change_password')
                    : route('login')) }}"
            class="position-relative btn btn-light">
                <i class="fas fa-user"></i>
            </a>


        </div>
    </div>
</nav>
