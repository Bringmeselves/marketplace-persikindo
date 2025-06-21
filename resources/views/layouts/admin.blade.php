<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin - Persikindo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Vite Tailwind & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        html, body {
            font-family: 'Poppins', sans-serif;
        }
        .btn-primary {
            @apply inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition;
        }
        .btn-danger {
            @apply inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition;
        }
    </style>
</head>
<body class="bg-[#f1f5f9] text-gray-800 min-h-screen">

{{-- 🔒 Validasi role admin --}}
@php
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'Akses ditolak. Halaman ini hanya untuk Admin.');
    }
@endphp

<div class="flex min-h-screen">
    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r flex flex-col justify-between">
        <div>
            <div class="px-6 py-6 text-2xl font-bold border-b">PERSIKINDO</div>
            <nav class="p-6">
                <ul class="space-y-4">
                    <li>
                        <a href="{{ route('admin.anggota.index') }}" class="block font-semibold text-gray-700 hover:text-blue-600">
                            Kelola Anggota
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.toko.index') }}" class="block font-semibold text-gray-700 hover:text-blue-600">
                            Kelola Toko
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.produk.index') }}" class="block font-semibold text-gray-700 hover:text-blue-600">
                            Kelola Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kategori.index') }}" class="block font-semibold text-gray-700 hover:text-blue-600">
                            Kelola Kategori
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- Profil & Logout --}}
        <div class="p-6 border-t">
            @php
                $user = auth()->user()->fresh();
                $photoUrl = $user->photo ? Storage::url($user->photo) : asset('images/default-avatar.png');
            @endphp
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $photoUrl }}" alt="Profil" class="w-10 h-10 rounded-full object-cover border" />
                <div>
                    <div class="text-sm font-semibold">{{ $user->name }}</div>
                    <a href="{{ route('profile.edit') }}" class="text-xs text-blue-500 hover:underline">Edit Profil</a>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-danger w-full">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Konten --}}
    <main class="flex-1 p-6 overflow-y-auto">
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>
</div>

{{-- Loader --}}
<div id="loader" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); z-index: 9999; display: flex; justify-content: center; align-items: center;">
    <div class="spinner"></div>
</div>

{{-- Spinner Style --}}
<style>
    .spinner {
        width: 48px;
        height: 48px;
        border: 6px solid #ccc;
        border-top: 6px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

{{-- Loader Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function () {
                const loader = document.getElementById('loader');
                if (loader) loader.style.display = 'flex';
            });
        });
    });

    window.addEventListener('load', function () {
        const loader = document.getElementById('loader');
        if (loader) loader.style.display = 'none';
    });

    // Render Lucide Icons
    lucide.createIcons();
</script>

{{-- Script Halaman Tambahan --}}
@yield('scripts')
</body>
</html>
