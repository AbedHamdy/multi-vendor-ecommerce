<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-right">
                <ul class="top-menu">
                    <li>
                        <a href="#">Links</a>
                        <ul>
                            <li><a href="{{ route('about') }}">About Us</a></li>
                            <li><a href="contact.html">Contact Us</a></li>
                            @guest
                                <li>
                                    <a href="{{ route('login') }}" data-toggle="modal">
                                        <i class="icon-user"></i> Login
                                    </a>
                                </li>
                            @endguest

                            @auth
                                <li>
                                    <a href="{{ route('logout_user') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="icon-user"></i> Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout_user') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            @endauth

                        </ul>
                    </li>
                </ul><!-- End .top-menu -->
            </div><!-- End .header-right -->
        </div><!-- End .container -->
    </div><!-- End .header-top -->

    <div class="header-middle sticky-header">
        <div class="container">
            <div class="header-left">
                <button class="mobile-menu-toggler">
                    <span class="sr-only">Toggle mobile menu</span>
                    <i class="icon-bars"></i>
                </button>

                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('images/icons/Bazario-logo-transparent.png') }}" alt="Bazario Logo"
                        width="105" height="25">
                </a>

                <nav class="main-nav">
                    <ul class="menu sf-arrows">
                        <li class="megamenu-container {{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">Home</a>
                        </li>

                        <li class="megamenu-container {{ request()->routeIs('view_packages') ? 'active' : '' }}">
                            <a href="{{ route('view_packages') }}">Packages</a>
                        </li>

                        <li>
                            <a href="{{ route('view_product') }}" class="sf-with-ul">Departments</a>
                            <div class="megamenu megamenu-md {{ request()->routeIs('view_product') ? 'active' : '' }}">
                                <div class="row no-gutters">
                                    <div class="col-md-8">
                                        <div class="menu-col">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="menu-title">Shop by Department</div>
                                                    <!-- End .menu-title -->
                                                    <ul>
                                                        @foreach ($departments as $department)
                                                            <li>
                                                                <a
                                                                    href="{{ route('view_product', ['department_id' => $department->id]) }}">
                                                                    {{ $department->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div><!-- End .col-md-6 -->
                                            </div><!-- End .row -->
                                        </div><!-- End .menu-col -->
                                    </div><!-- End .col-md-8 -->
                                </div><!-- End .row -->
                            </div><!-- End .megamenu megamenu-md -->
                        </li>

                        <li>
                            <a href={{ route('view_product') }}>Product</a>
                        </li>

                        <li>
                            <a href="" class="sf-with-ul">Pages</a>

                            <ul>
                                <li><a href="{{ route('about') }}">About</a></li>
                                {{-- <li><a href="contact.html">Contact</a></li> --}}
                                <li><a href="{{ route('login') }}">Login</a></li>
                            </ul>
                        </li>
                    </ul><!-- End .menu -->
                </nav><!-- End .main-nav -->
            </div><!-- End .header-left -->

            <div class="header-right">
                <div class="dropdown cart-dropdown">
                    <a href="{{ route('view_cart') }}" class="dropdown-toggle" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" data-display="static">
                        <i class="icon-shopping-cart"></i>
                        <span class="cart-count">{{ $cartCount }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
