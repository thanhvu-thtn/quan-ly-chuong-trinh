<!DOCTYPE html>
<html lang="vi">
@include('layouts.head')

<body>
    @include('layouts.navbar')
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="min-height: 100vh;">
            <div class="position-sticky pt-3">
                @include('layouts.sidebar')
            </div>
        </nav>
        @include('partials.alerts')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-4">
                @yield('content')
            </main>
            <footer>
                <div class="text-center py-3">
                    &copy; 2026 QLCT Vật Lý THPT. All rights reserved.
                </div>
            </footer>
        </div>
    </div>

    <!-- JS Bootstrap -->
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
