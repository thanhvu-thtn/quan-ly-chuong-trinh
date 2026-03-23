<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QLCT Vật Lý THPT - @yield('title')</title>
    <!-- Gọi Bootstrap từ thư mục public đã chép ở bước trước -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}">
    <style>
        /* Tùy chỉnh chiều cao sidebar để full màn hình */
        .sidebar {
            min-height: calc(100vh - 56px); /* 56px là chiều cao dự kiến của navbar */
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
        }
    </style>
</head>