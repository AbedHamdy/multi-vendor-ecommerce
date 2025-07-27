<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazario - Everything you need , all in one spot</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('images/icons/logo (1).svg') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('images/icons/Bazario-logo.png') }}">

    <!-- Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700,800" rel="stylesheet">

    <!-- Vendor CSS (1) -->
    <link rel="stylesheet" href="{{ asset('lib/bootstrap/bootstrap.min.css') }}">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/main.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('fonts/icon51c1.eot') }}"> --}}

</head>

<body>
    <div class="page-wrapper">
        <!-- Header -->
        <header id="header">
            <div class="container-lg">
                <div class="header-left">
                    <div class="logo">
                        <img src="{{ asset('images/icons/Bazario-logo-transparent.png') }}" alt="Bazario Logo"
                            style="max-height: 60px; width: auto;">
                    </div>
                </div>
                <div class="header-main">
                    <ul class="menu">
                        <li><a href="{{ route('view_packages') }}" class="goto-demos">Package</a></li>
                        <li><a href="{{ route('login') }}" class="goto-features">Login</a></li>
                        <li><a href="{{ route('view_department') }}" class="goto-elements">Departments</a></li>
                        {{-- <li><a href="#" class="goto-support">Support</a></li> --}}
                    </ul>
                </div>
                <div class="header-right">
                    <a class="mobile-menu-toggler mr-0 mr-sm-5"><i class="icon-bars"></i></a>
                    <a class="btn btn-primary btn-outline"><i class="icon-shopping-cart"></i>Shopping Now</a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div id="main">
            <!-- Banner Section -->
            <section class="banner section-dark" style="background: #222;">
                <img src="{{ asset('images/demos-img/header_splash.jpg') }}" alt="" width="1920"
                    height="1120">
                <div class="banner-text text-center">
                    <h1>Bazario - Multi Vendor E-Commerce</h1>
                    <h5 class="mb-5">Everything you need , all in one spot</h5>
                    <p class="mb-0"><a href="#" class="btn btn-primary btn-outline goto-demos">Start shopping
                            now and explore our top products!<i class="icon-long-arrow-alt-down"></i></a></p>
                </div>
            </section>

            <!-- Demos Section -->
            <section class="section section-demos text-center container-lg">
                <h2>Shop by Department</h2>
                <p>
                    Find everything you need in one place – from the latest fashion trends to cutting-edge electronics.
                    <br>
                    At Bazario, we've organized our top Departments to make your shopping fast, easy, and enjoyable.
                    <br>
                    Browse your favorite sections and discover amazing deals tailored just for you.
                </p>

                <!-- أسماء الأقسام -->
                <div class="demo-filter menu">
                    @foreach ($departments as $index => $department)
                        <a href="#" class="category-tab {{ $index == 0 ? 'active' : '' }}"
                            data-category="{{ $department->id }}">
                            {{ $department->name }}
                        </a>
                    @endforeach
                </div>

                <!-- عرض المنتجات -->
                <div class="row demos">
                    @foreach ($departments as $departmentIndex => $department)
                        @foreach ($department->products as $product)
                            <div class="iso-item col-sm-6 col-md-4 col-lg-3 products-{{ $department->id }}"
                                style="{{ $departmentIndex == 0 ? 'display: block;' : 'display: none;' }}">
                                <a href="{{ route('product.show', $product->id) }}">
                                    <img src="{{ $product->mainImage ? asset('images/products/' . $product->mainImage->image) : asset('images/demos/demo-2/banners/banner-1.jpg') }}"
                                        width="500" height="385" alt="{{ $product->name }}">
                                    <h5>{{ $product->name }}</h5>
                                    {{-- @if ($product->price)
                                        <p class="product-price">${{ number_format($product->price, 2) }}</p>
                                    @endif --}}
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </section>

            <!-- Features Section -->
            <section class="features-section py-5">
                <div class="container">
                    <h2 class="text-center mb-3">Why Choose Bazario?</h2>
                    <p class="text-center mb-5">Powerful features that make Bazario your top choice for smart and
                        convenient shopping.</p>

                    <div class="row">
                        <!-- Feature 1 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-shopping-cart"></i>
                                <h4>Easy Shopping Experience</h4>
                                <p>A simple and fast interface for an enjoyable and smooth shopping journey.</p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-star"></i>
                                <h4>Secure & Multiple Payment Options</h4>
                                <p>Choose from various fast and secure payment methods.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-star"></i>
                                <h4>Fast Shipping</h4>
                                <p>Quick delivery service to all governorates.</p>
                            </div>
                        </div>

                        <!-- Feature 4 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-star"></i>
                                <h4>Return Guarantee</h4>
                                <p>Easy product returns within the warranty period.</p>
                            </div>
                        </div>

                        <!-- Feature 5 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-star"></i>
                                <h4>Trusted Products</h4>
                                <p>Carefully selected items from top suppliers.</p>
                            </div>
                        </div>

                        <!-- Feature 6 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-star"></i>
                                <h4>24/7 Support</h4>
                                <p>Our support team is available around the clock to help you.</p>
                            </div>
                        </div>

                        <!-- Feature 7 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-star"></i>
                                <h4>Deals & Discounts</h4>
                                <p>Enjoy the best prices and exclusive offers every day.</p>
                            </div>
                        </div>

                        <!-- Feature 8 -->
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="icon-box text-center">
                                <i class="icon-star"></i>
                                <h4>Privacy & Security</h4>
                                <p>Full protection of your personal data and complete confidentiality.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section section-light section-ready container text-center">
                <h2 class="mb-3">Ready to Shop at Bazario?</h2>
                <p>Discover top products and best deals today!</p>
                <div class="star-rating mb-4 pb-3">
                    <i class="icon-star"></i><i class="icon-star"></i><i class="icon-star"></i><i
                        class="icon-star"></i><i class="icon-star"></i>
                </div>
                <p>
                    <a class="btn btn-primary btn-outline" href="{{ route('view_packages') }}">
                        <i class="icon-shopping-cart"></i> Start Shopping
                    </a>
                </p>
            </section>
        </div>

        <!-- Footer -->
        <footer id="footer" class="container-lg">
            <div class="row">
                <div class="col-md-6 text-center text-md-left mb-4 mb-md-0">
                    <p class="copyright mb-0"><a href="">Abed</a></p>
                </div>
                <div class="col-md-6 text-center text-md-right social-icons">
                    <label class="mr-3">Social Media</label>
                    <a href="#" title="Facebook"><i class="icon-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="icon-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="icon-instagram"></i></a>
                    <a href="#" title="Youtube"><i class="icon-youtube"></i></a>
                    <a href="#" title="Pinterest"><i class="icon-pinterest"></i></a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay"></div>
    <div class="mobile-menu-container">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-close"></i></span>
            <nav class="mobile-nav">
                <ul class="mobile-menu">
                    <li><a href="{{ route('view_packages') }}" class="goto-demos">Package</a></li>
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('view_department') }}">Departments</a></li>
                    {{-- <li><a href="#">Support</a></li> --}}
                </ul>
            </nav>
            <div class="d-flex justify-content-center social-icons">
                <a href="#" class="social-icon" title="Facebook"><i class="icon-facebook-f"></i></a>
                <a href="#" class="social-icon" title="Twitter"><i class="icon-twitter"></i></a>
                <a href="#" class="social-icon" title="Instagram"><i class="icon-instagram"></i></a>
                <a href="#" class="social-icon" title="Youtube"><i class="icon-youtube"></i></a>
            </div>
        </div>
    </div>

    <!-- Vendor Scripts -->
    <script src="{{ asset('lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lib/jquery.appear/jquery.appear.min.js') }}"></script>
    <script src="{{ asset('lib/jquery.lazyload/jquery.lazyload.min.js') }}"></script>
    <script src="{{ asset('lib/isotope/jquery.isotope.min.js') }}"></script>

    <!-- Theme Scripts -->
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>
