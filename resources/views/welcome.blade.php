<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome to Our Marketplace</title>

    <!-- Import Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-poppins">

    <!-- Navbar -->
    <nav class="flex justify-between items-center p-6 bg-white shadow-md">
        <a href="#" class="text-2xl font-bold text-indigo-600">Marketplace</a>
        <div class="space-x-4">
            <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600 font-semibold">Login</a>
            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">Register</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center px-6">
        <div class="max-w-4xl text-center space-y-8">
            <h1 class="text-5xl font-extrabold text-indigo-700">Welcome to Our Marketplace</h1>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                Discover the best products and services from trusted sellers.
                Join our community to buy and sell with ease and confidence.
                Whether you're looking for unique items or want to grow your business, we're here to help.
            </p>
            <a href="{{ route('register') }}" 
               class="inline-block bg-indigo-600 text-white font-semibold px-8 py-3 rounded-md shadow-lg hover:bg-indigo-700 transition">
                Join Us Now
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-inner p-4 text-center text-gray-500 text-sm">
        &copy; {{ date('Y') }} Marketplace. All rights reserved.
    </footer>

</body>
</html>
