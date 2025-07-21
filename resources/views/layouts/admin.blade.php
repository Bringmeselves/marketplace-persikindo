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
    <aside class="w-64 bg-white border-r shadow-sm sticky top-0 h-screen overflow-y-auto flex flex-col justify-between">
        <div>
            <div class="px-6 py-6 text-2xl font-bold border-b text-gray-900">
                PERSIKINDO
            </div>
            <nav class="p-6">
                <ul class="space-y-4 text-sm text-gray-700 font-medium">
                    {{-- Menu Utama --}}
                    <li>
                        <a href="{{ route('admin.anggota.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition">
                            <i data-lucide="users" class="w-5 h-5"></i>
                            Anggota
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.toko.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition">
                            <i data-lucide="store" class="w-5 h-5"></i>
                            Toko
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.produk.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition">
                            <i data-lucide="box" class="w-5 h-5"></i>
                            Produk
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition">
                            <i data-lucide="tag" class="w-5 h-5"></i>
                            Kategori
                        </a>
                    </li>
                </ul>

                <hr class="my-6 border-gray-200" />

                <ul class="space-y-4 text-sm text-gray-700 font-medium">
                    {{-- Menu Transaksi --}}
                    <li>
                        <a href="{{ route('admin.penarikan.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition">
                            <i data-lucide="wallet" class="w-5 h-5"></i>
                            Penarikan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.transaksi.index') }}" class="flex items-center gap-3 hover:text-blue-600 transition">
                            <i data-lucide="receipt" class="w-5 h-5"></i>
                            Transaksi
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- Profil & Logout --}}
        <div class="px-6 py-4 border-t bg-gray-50">
            @php
                $user = auth()->user()->fresh();
                $photoUrl = $user->photo ? Storage::url($user->photo) : asset('images/default-avatar.png');
            @endphp
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ $photoUrl }}" alt="Profil" class="w-10 h-10 rounded-full object-cover border" />
                <div>
                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                    <a href="{{ route('profile.edit') }}" class="text-xs text-blue-500 hover:underline">Edit Profil</a>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 rounded-lg transition">
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

{{-- Lucide Icon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>

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
