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
    <footer class="bg-white text-black text-center py-4 border-t">
        <p>&copy; 2025 Persikindo. All rights reserved.</p>
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
