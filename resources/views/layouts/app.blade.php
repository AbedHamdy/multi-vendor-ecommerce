<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>

        @include("layouts.head")

    </head>
    <body>
        <!-- Navbar -->

        @include("layouts.navbar")

        <!-- Sidebar -->

        @include("layouts.sidebar")

        <!-- Overlay for mobile -->
        <div class="overlay" id="overlay" onclick="closeSidebar()"></div>

        <!-- Main Content -->
        <main class="main-content" id="main-content">

            @yield('content')

        </main>

        <!-- Footer -->

        @include("layouts.footer")


        @include("layouts.script")
        @stack('scripts')

    </body>
</html>
