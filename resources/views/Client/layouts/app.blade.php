<!DOCTYPE html>
<html lang="en">


<!-- molla/login.html  22 Nov 2019 10:04:03 GMT -->
<head>

    @include("Client.layouts.head")
</head>

<body>
    <div class="page-wrapper">

        @include("Client.layouts.navbar")

        <main class="main">

            @yield("content")

        </main><!-- End .main -->

        {{-- footer --}}
        @include("Client.layouts.footer")
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay"></div><!-- End .mobil-menu-overlay -->

    @include("Client.layouts.nav")

    <!-- Sign in / Register Modal -->

    <!-- Plugins JS File -->
    @include("Client.layouts.script")
</body>


<!-- molla/login.html  22 Nov 2019 10:04:03 GMT -->
</html>
