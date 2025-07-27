<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Marketplace Persikindo')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token untuk AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Vite asset untuk Tailwind dan JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Fonts: Poppins --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js" defer></script>

    <style>
        html, body {
            height: 100%;
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-[#f1f5f9] h-screen w-full text-gray-800">

<div class="w-full h-full flex flex-col bg-white overflow-hidden">

    {{-- Header/Navbar --}}
    <header class="flex items-center justify-between px-10 py-6 border-b bg-white relative">
        <div class="logo text-2xl font-bold">PERSIKINDO</div>

        {{-- Navigasi --}}
        <nav>
            <ul class="flex gap-6 list-none items-center">
                @if(auth()->check())
                    @php $user = auth()->user()->fresh(); @endphp

                    @if($user->role === 'user')
                        <li>
                            <a href="{{ route('user.anggota.create') }}" class="flex items-center gap-1 text-black font-semibold hover:text-blue-500">
                                <i data-lucide="user-plus" class="w-5 h-5"></i> Daftar Anggota
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.marketplace.index') }}" class="flex items-center gap-1 text-black font-semibold hover:text-blue-500">
                                <i data-lucide="home" class="w-5 h-5"></i> Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.transaksi.index') }}" class="flex items-center gap-1 text-black font-semibold hover:text-blue-500">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i> Pesananmu
                            </a>
                        </li>

                    @elseif($user->role === 'anggota')
                        <li>
                            <a href="{{ route('user.marketplace.index') }}" class="flex items-center gap-1 text-black font-semibold hover:text-blue-500">
                                <i data-lucide="home" class="w-5 h-5"></i> Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('user.transaksi.index') }}" class="flex items-center gap-1 text-black font-semibold hover:text-blue-500">
                                <i data-lucide="shopping-bag" class="w-5 h-5"></i> Pesananmu
                            </a>
                        </li>
                        @if($user->toko)
                            <li>
                                <a href="{{ route('user.toko.kelola', ['id' => $user->toko->id]) }}" class="flex items-center gap-1 text-black font-semibold hover:text-blue-500">
                                    <i data-lucide="store" class="w-5 h-5"></i> Toko Saya
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('user.toko.create') }}" class="flex items-center gap-1 text-black font-semibold hover:text-blue-500">
                                    <i data-lucide="plus-circle" class="w-5 h-5"></i> Buat Toko
                                </a>
                            </li>
                        @endif
                    @endif
                @endif
            </ul>
        </nav>

    {{-- Wrapper Chat dan Dropdown User --}}
    @auth
        <div class="flex items-center gap-5 relative" x-data="{ openUser: false }">

            {{-- Tombol Keranjang --}}
            <a href="{{ route('user.keranjang.index') }}"
                class="inline-flex items-center justify-center text-black hover:text-gray-700 p-2 rounded transition"
                aria-label="Keranjang">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i>
            </a>

            {{-- Tombol Chat dengan Notifikasi --}}
            <a href="{{ route('user.chat.index') }}"
            class="relative inline-flex items-center justify-center text-black hover:text-gray-700 p-2 rounded transition"
            aria-label="Chat">
                <i data-lucide="message-circle" class="w-5 h-5"></i>

                {{-- Indikator Notifikasi --}}
                @php
                    $userId = auth()->id();
                    $toko = \App\Models\Toko::where('user_id', $userId)->first();

                    $unreadCount = \App\Models\Chat::where(function ($q) use ($userId, $toko) {
                            $q->where('user_id', $userId);
                            if ($toko) {
                                $q->orWhere('toko_id', $toko->id);
                            }
                        })
                        ->whereHas('pesan', function ($q) use ($userId) {
                            $q->where('sudah_dibaca', false)
                            ->where('user_id', '!=', $userId);
                        })
                        ->count();
                @endphp

                @if($unreadCount > 0)
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white animate-ping"></span>
                    <span class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 rounded-full ring-2 ring-white"></span>
                @endif
            </a>

            {{-- Dropdown Pengguna --}}
            @php
                $photoUrl = $user->photo ? Storage::url($user->photo) : asset('images/default-avatar.png');
            @endphp

            <div class="relative">
                <button @click="openUser = !openUser"
                    class="flex items-center gap-2 p-2 rounded hover:bg-gray-100 transition"
                    aria-label="User menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Dropdown User --}}
                <div x-show="openUser" @click.away="openUser = false" x-transition
                    class="absolute right-0 top-full mt-2 w-48 bg-white border rounded-lg shadow-lg z-50">
                    <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm hover:bg-gray-100">
                        <img src="{{ $photoUrl }}" alt="Profil" class="w-6 h-6 rounded-full object-cover mr-2 border" />
                        Profil
                    </a>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </div>
    @endauth
</header>

    {{-- Konten --}}
    <main class="p-6 flex-1 overflow-y-auto">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer x-data="{ open: true }" class="bg-white border-t text-gray-700 text-sm relative">

        {{-- Tombol Segitiga Toggle di Kanan Atas --}}
        <div class="absolute right-4 top-2">
            <button @click="open = !open"
                class="transition-transform duration-300 text-gray-500 hover:text-gray-700 focus:outline-none"
                :class="{ 'rotate-180': !open }">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
        </div>

        {{-- Konten Footer --}}
        <div x-show="open" x-transition class="max-w-6xl mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Kolom 1: Tentang --}}
            <div>
                <h3 class="text-base font-semibold mb-2">Tentang PERSIKINDO</h3>
                <p class="leading-relaxed">
                    PERSIKINDO adalah wadah kolaborasi pelaku UMKM Indonesia untuk memasarkan produk dan jasa secara digital. 
                    Kami mendukung pertumbuhan ekonomi lokal melalui teknologi.
                </p>
            </div>

            {{-- Kolom 2: Customer Service --}}
            <div>
                <h3 class="text-base font-semibold mb-2">Customer Service</h3>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2">
                        {{-- WhatsApp --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M21 11.5a8.38 8.38 0 01-.9 3.8A8.5 8.5 0 0112.5 21a8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.5 8.5 0 018 8v.5z" />
                        </svg>
                        <a href="https://wa.me/6281234567890" target="_blank" class="hover:underline">+62 812-3456-7890</a>
                    </li>
                    <li class="flex items-center gap-2">
                        {{-- Email --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16v16H4V4z" />
                            <path d="M4 4l8 8 8-8" />
                        </svg>
                        <a href="mailto:support@persikindo.or.id" class="hover:underline">support@persikindo.or.id</a>
                    </li>
                    <li class="flex items-center gap-2">
                        {{-- Telepon --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-700" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 01-2.18 2A19.73 19.73 0 012 4.18 2 2 0 014 2h3a2 2 0 012 1.72 12.05 12.05 0 001.11 3.62 2 2 0 01-.45 2.11l-1.27 1.27a16 16 0 006.11 6.11l1.27-1.27a2 2 0 012.11-.45 12.05 12.05 0 003.62 1.11A2 2 0 0122 16.92z" />
                        </svg>
                        <span>(021) 1234-5678</span>
                    </li>
                </ul>
            </div>

            {{-- Kolom 3: Sosial Media --}}
            <div>
                <h3 class="text-base font-semibold mb-2">Ikuti Kami</h3>
                <div class="flex items-center space-x-4">
                    {{-- Instagram --}}
                    <a href="https://instagram.com/persikindo" target="_blank" class="text-gray-600 hover:text-pink-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                            <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z" />
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                        </svg>
                    </a>
                    {{-- Facebook --}}
                    <a href="https://facebook.com/persikindo" target="_blank" class="text-gray-600 hover:text-blue-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M22 12a10 10 0 10-11.5 9.87v-6.99h-2.5v-2.88h2.5V9.5c0-2.47 1.48-3.84 3.74-3.84 1.08 0 2.2.2 2.2.2v2.42h-1.24c-1.22 0-1.6.76-1.6 1.54v1.85h2.72l-.43 2.88h-2.29v6.99A10 10 0 0022 12z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t mt-6 py-4 text-center text-gray-500 text-xs">
            &copy; 2025 PERSIKINDO. All rights reserved.
        </div>
    </footer>
</div>

{{-- Section script tambahan --}}
@stack('scripts')

<!-- Loader Overlay -->
<div id="loader" style="
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(255, 255, 255, 0.9);
    z-index: 9999;
    display: flex;
    justify-content: center;
    align-items: center;
">
    <div class="spinner"></div>
</div>

<!-- CSS Spinner -->
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

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const loader = document.getElementById('loader');

        // Tangani khusus form hapus item dengan SweetAlert2
        document.querySelectorAll('.form-delete-item').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Cegah submit langsung
                Swal.fire({
                    title: 'Hapus item ini?',
                    text: "Produk akan dihapus dari checkout.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (loader) loader.style.display = 'flex'; // Tampilkan loader hanya jika dikonfirmasi
                        form.submit();
                    }
                });
            });
        });

        // Tampilkan loader untuk form lain (selain form hapus item)
        const forms = document.querySelectorAll('form:not(.form-delete-item)');
        forms.forEach(form => {
            form.addEventListener('submit', function () {
                if (loader) {
                    loader.style.display = 'flex';
                }
            });
        });
    });

    // Sembunyikan loader setelah halaman selesai dimuat
    window.addEventListener('load', function () {
        const loader = document.getElementById('loader');
        if (loader) {
            loader.style.display = 'none';
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
