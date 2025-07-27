@extends('layouts.app')

@section('title', $toko->nama_toko)

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">

    {{-- === PROFIL TOKO === --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-6">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="store" class="w-6 h-6 text-indigo-500"></i>
                {{ $toko->nama_toko }}
            </h2>
            <p class="text-sm text-gray-500">Informasi lengkap mengenai toko ini.</p>
        </div>

        {{-- Isi Profil --}}
        <div class="flex flex-col md:flex-row items-start gap-6">
            {{-- Foto Toko --}}
            <div class="w-28 h-28 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center border border-gray-200">
                @if($toko->foto_toko && file_exists(public_path('storage/' . $toko->foto_toko)))
                    <img src="{{ asset('storage/' . $toko->foto_toko) }}" alt="Foto Toko" class="w-full h-full object-cover">
                @else
                    <i data-lucide="image-off" class="w-6 h-6 text-gray-400"></i>
                @endif
            </div>

            {{-- Detail Toko --}}
            <div class="flex-1 space-y-3">
                {{-- Keterangan --}}
                @if ($toko->keterangan)
                    <div x-data="{ expanded: false }" class="text-sm text-gray-600">
                        @php
                            $keterangan = $toko->keterangan;
                            $maxLength = 120;
                        @endphp
                        @if (strlen($keterangan) > $maxLength)
                            <p>
                                <span x-show="!expanded">{{ \Illuminate\Support\Str::limit($keterangan, $maxLength) }}</span>
                                <span x-show="expanded">{{ $keterangan }}</span>
                                <button @click="expanded = !expanded" class="text-indigo-600 hover:underline ml-1" x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></button>
                            </p>
                        @else
                            <p>{{ $keterangan }}</p>
                        @endif
                    </div>
                @endif

                {{-- Kontak dan Lokasi --}}
                <div class="space-y-1 text-sm text-gray-600">
                    @if ($toko->alamat)
                        <p class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                            {{ $toko->alamat }}
                        </p>
                    @endif
                    @if ($toko->city_name)
                        <p class="flex items-center gap-2">
                            <i data-lucide="city" class="w-4 h-4 text-gray-400"></i>
                            {{ $toko->city_name }}
                        </p>
                    @endif
                    @if ($toko->nomer_wa)
                        <p class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $toko->nomer_wa) }}" target="_blank" class="text-indigo-600 font-medium hover:underline">
                                {{ $toko->nomer_wa }}
                            </a>
                        </p>
                    @endif
                </div>

                {{-- Tombol Chat --}}
                <form action="{{ route('user.chat.mulai', $toko->id) }}" method="GET" class="inline-block pt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition shadow">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        Chat Penjual
                    </button>
                </form>

                {{-- Form Penilaian Toko --}}
                <div class="mt-8 bg-white rounded-xl p-6 border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Penilaian Toko</h2>

                    @auth
                        @if (!$sudahNilaiToko)
                            <form action="{{ route('user.penilaian-toko.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="toko_id" value="{{ $toko->id }}">

                                {{-- Rating --}}
                                <div>
                                    <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                                    <select name="rating" id="rating" required class="mt-1 w-full border-gray-300 rounded-lg shadow-sm">
                                        <option value="">Pilih rating</option>
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }} bintang</option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Ulasan --}}
                                <div>
                                    <label for="ulasan" class="block text-sm font-medium text-gray-700">Ulasan (opsional)</label>
                                    <textarea name="ulasan" id="ulasan" rows="3" class="mt-1 w-full border-gray-300 rounded-lg shadow-sm"></textarea>
                                </div>

                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Kirim Penilaian
                                </button>
                            </form>
                        @else
                            <div class="text-sm text-gray-600">Anda sudah memberikan penilaian untuk toko ini.</div>
                        @endif
                    @else
                        <div class="text-sm text-gray-600">Silakan login untuk memberi penilaian.</div>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- === PRODUK TOKO === --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-6">

        <div class="border-b pb-4">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="package" class="w-5 h-5 text-gray-500"></i>
                Produk dari toko ini
            </h2>
        </div>

        @if ($produk->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($produk as $item)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col overflow-hidden group">
                        {{-- Gambar dengan Jumlah Terjual --}}
                        <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">

                            {{-- Jumlah Terjual (badge di pojok kiri atas) --}}
                            <div class="absolute top-2 left-2 bg-white/90 text-gray-700 text-xs px-2 py-1 rounded shadow flex items-center gap-1 z-10">
                                <i data-lucide="shopping-bag" class="w-3.5 h-3.5 text-gray-500"></i>
                                <span>{{ $item->jumlah_terjual ?? 0 }} terjual</span>
                            </div>

                            {{-- Gambar Produk --}}
                            <img
                                src="{{ $item->gambar ? asset('storage/' . $item->gambar) : asset('images/default-produk.png') }}"
                                alt="{{ $item->nama }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                        </div>
                        {{-- Detail Produk --}}
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-900 truncate" title="{{ $item->nama }}">
                                    {{ $item->nama }}
                                </h3>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                    {{ $item->deskripsi ?? '-' }}
                                </p>

                                 {{-- Rating dan Jumlah Ulasan --}}
                                @php
                                    $jumlahUlasan = $item->penilaian->count();
                                    $rataRating = $jumlahUlasan ? round($item->penilaian->avg('rating'), 1) : null;
                                @endphp

                                @if ($jumlahUlasan > 0)
                                    <div class="flex items-center mt-2 gap-1 text-sm">
                                        {{-- Satu ikon bintang --}}
                                        <i data-lucide="star" class="w-4 h-4 text-yellow-400"></i>

                                        {{-- Nilai rata-rata dan jumlah ulasan --}}
                                        <span class="text-gray-700">
                                            {{ $rataRating }} ({{ $jumlahUlasan }} ulasan)
                                        </span>
                                    </div>
                                @else
                                    <div class="text-sm text-gray-400 mt-2">Belum ada ulasan</div>
                                @endif
                                
                            </div>
                            <p class="text-lg font-bold text-gray-900 mt-2">
                                Rp{{ number_format($item->harga, 0, ',', '.') }}
                            </p>
                        </div>
                        {{-- Tombol Aksi: Beli & Tambah ke Keranjang --}}
                        @if ($item->user->anggota && $item->user->anggota->status === 'rejected')
                            <button disabled class="w-full bg-gray-300 text-gray-600 py-2 rounded-xl cursor-not-allowed">
                                Tidak tersedia
                            </button>
                        @else

                        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                            <div class="flex items-center gap-2">
                                {{-- Tombol Beli --}}
                                <form action="{{ route('user.pembelian.create', $item->id) }}" method="GET" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition-all flex justify-center items-center gap-2">
                                        <i data-lucide="shopping-cart" class="w-4 h-4"></i> Beli
                                    </button>
                                </form>

                                {{-- Tombol Tambah ke Keranjang --}}
                                <form action="{{ route('user.keranjang.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="produk_id" value="{{ $item->id }}">
                                    <input type="hidden" name="varian_id" value="{{ $item->varian->first()->id ?? '' }}">

                                    <button type="submit" class="bg-green-500 text-white p-2 rounded-xl hover:bg-green-600 transition">
                                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                                    </button>
                                </form>
                            </div>
                        @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex justify-center">
                {{ $produk->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center text-gray-400 py-12">
                <i data-lucide="box" class="w-8 h-8 mx-auto mb-2"></i>
                Toko ini belum memiliki produk.
            </div>
        @endif
    </div>

</div>

{{-- AlpineJS --}}
<script src="https://unpkg.com/alpinejs" defer></script>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

{{-- SweetAlert Notification --}}
@if (session('success') || session('error') || session('welcome') || session('catatan_penolakan'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    html: `<p style="margin: 0;">{{ session('success') }}</p>`,
                    iconColor: '#10b981', // green-500
                    background: '#ffffff',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: `<p style="margin: 0;">{{ session('error') }}</p>`,
                    iconColor: '#ef4444', // red-500
                    background: '#ffffff',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#ef4444',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('welcome'))
                Swal.fire({
                    icon: 'info',
                    title: 'Selamat Datang',
                    html: `<p style="margin: 0;">{{ session('welcome') }}</p>`,
                    iconColor: '#3b82f6', // blue-500
                    background: '#ffffff',
                    confirmButtonText: 'Terima Kasih',
                    confirmButtonColor: '#3b82f6',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('catatan_penolakan'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengajuan Ditolak',
                    html: `{!! nl2br(e(session('catatan_penolakan'))) !!}`,
                    iconColor: '#f59e0b', // amber-500
                    background: '#ffffff',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#f59e0b',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif
        });
    </script>

    <style>
        .swal-attractive-popup {
            border-radius: 1rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
        }

        .swal-attractive-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .swal-attractive-button {
            font-size: 14px !important;
            font-weight: 600;
            padding: 10px 20px !important;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .swal-attractive-button:hover {
            filter: brightness(0.95);
        }
    </style>
@endif
@endsection
