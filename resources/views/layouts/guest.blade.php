<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'Persikindo') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Vite asset untuk Tailwind dan JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
            background-image: url('/images/persikindo3.jpeg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center text-gray-800">
    <div class="w-full max-w-md p-8 rounded-lg">
        {{ $slot }}
    </div>
</body>
</html>
