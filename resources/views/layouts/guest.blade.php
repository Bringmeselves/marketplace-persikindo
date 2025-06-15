<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Persikindo') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        html, body {
            margin: 0;
            height: 100%;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        .slideshow {
            position: fixed;
            z-index: 0;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            animation: slide 24s infinite ease-in-out;
        }

        .slideshow img {
            min-width: 100%;
            height: 100%;
            object-fit: cover;
        }

        @keyframes slide {
            0%   { transform: translateX(0%); }
            33%  { transform: translateX(-100%); }
            66%  { transform: translateX(-200%); }
            100% { transform: translateX(0%); }
        }

        .slideshow-container {
            display: flex;
            width: 300%;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden text-white">
    <!-- Slideshow -->
    <div class="slideshow">
        <div class="slideshow-container">
            <img src="/images/bg1.jpg" alt="Slide 1">
            <img src="/images/bg2.jpg" alt="Slide 2">
            <img src="/images/bg3.jpg" alt="Slide 3">
        </div>
    </div>

    <!-- Slot content -->
    <div class="z-10 w-full px-4">
        {{ $slot }}
    </div>
</body>
</html>
