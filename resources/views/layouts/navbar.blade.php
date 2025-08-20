<nav class="navbar">
    <div class="navbar-left">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars" id="menu-icon"></i>
        </button>
        <div class="logo">Dashboard</div>
    </div>
    <div class="navbar-right">
        <div class="search-box">
            <input type="text" placeholder="Search...">
            <i class="fas fa-search"></i>
        </div>
        <div class="navbar-icons">

            @php
                $user = auth("vendor")->user();
                $unreadCount = $user ? $user->unreadNotifications->count() : 0;
            @endphp

            <a href="{{ route('unread_notifications') }}" class="position-relative btn btn-light">
                <i class="fas fa-bell"></i>
                @if($unreadCount > 0)
                    <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                        {{ $unreadCount }}
                    </span>
                @endif
            </a>
            {{-- <button><i class="fas fa-bell"></i></button> --}}
            <button><i class="fas fa-user"></i></button>
            <button><i class="fas fa-cog"></i></button>
        </div>
    </div>
</nav>
