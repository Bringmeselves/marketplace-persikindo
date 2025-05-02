<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Marketplace Persikindo')</title>
    <!-- CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Tambahkan CSS lainnya di sini -->
</head>
<body>
    <div class="container">
        @include('layouts.navbar')  <!-- Navbar -->
        @yield('content')          <!-- Konten Halaman -->
    </div>
</body>
</html>
