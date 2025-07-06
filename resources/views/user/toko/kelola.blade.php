@extends('layouts.app')

@section('title', 'Kelola Toko')

@section('content')
<div class="max-w-6xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900 tracking-tight">Kelola Toko</h2>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded-lg shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- SECTION: Profil Toko --}}
    <div class="bg-white shadow-md rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start gap-6 border border-gray-100">
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
            <h4 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="store" class="w-5 h-5 text-indigo-500"></i>
                {{ $toko->nama_toko }}
            </h4>

            {{-- Keterangan --}}
            <div x-data="{ expanded: false }" class="text-sm text-gray-600">
                @php
                    $keterangan = $toko->keterangan;
                    $maxLength = 100;
                @endphp
                @if ($keterangan && strlen($keterangan) > $maxLength)
                    <p>
                        <span x-show="!expanded">{{ \Illuminate\Support\Str::limit($keterangan, $maxLength) }}</span>
                        <span x-show="expanded">{{ $keterangan }}</span>
                        <button @click="expanded = !expanded" class="text-indigo-600 hover:underline ml-1" x-text="expanded ? 'Sembunyikan' : 'Selengkapnya'"></button>
                    </p>
                @else
                    <p>{{ $keterangan ?: '-' }}</p>
                @endif
            </div>

            <div class="space-y-1 text-sm text-gray-600">
                <p class="flex items-center gap-1">
                    <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                    {{ $toko->alamat ?: '-' }}
                </p>
                <p class="flex items-center gap-1">
                    <i data-lucide="city" class="w-4 h-4 text-gray-400"></i>
                    {{ $toko->city_name ?? '-' }}
                </p>
                <p class="flex items-center gap-1">
                    <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                    <span class="text-indigo-600 font-medium">{{ $toko->nomer_wa ?: '-' }}</span>
                </p>
            </div>
        </div>

        {{-- Tombol Edit --}}
        <div class="md:ml-auto">
            <a href="{{ route('user.toko.edit', $toko->id) }}"
               class="inline-flex items-center gap-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-sm font-medium px-5 py-2 rounded-lg transition-all duration-200 shadow-sm">
                <i data-lucide="edit-3" class="w-4 h-4"></i>
                Edit Toko
            </a>
        </div>
    </div>

    {{-- SECTION: Aksi --}}
    <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('user.produk.create', ['toko_id' => $toko->id]) }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl transition font-semibold shadow">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Produk
        </a>
    </div>

    {{-- SECTION: Transaksi Masuk --}}
    <div class="pt-12 space-y-4">
        <h3 class="text-2xl font-semibold text-gray-900 flex items-center gap-2">
            <i data-lucide="shopping-bag" class="w-6 h-6 text-indigo-500"></i>
            Transaksi Masuk
        </h3>

        @forelse ($transaksiMasuk as $transaksi)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-2">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                    <div class="text-sm text-gray-700 space-y-1">
                        <p><span class="font-medium">Produk:</span> {{ $transaksi->produk->nama }}</p>
                        @if ($transaksi->varian)
                            <p><span class="font-medium">Varian:</span> {{ $transaksi->varian->nama }}</p>
                        @endif
                        <p><span class="font-medium">Pembeli:</span> {{ $transaksi->user->name }}</p>
                        <p><span class="font-medium">Status:</span> 
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ 
                                    $transaksi->status === 'diproses' ? 'bg-yellow-100 text-yellow-800' :
                                    ($transaksi->status === 'dikirim' ? 'bg-blue-100 text-blue-800' :
                                    ($transaksi->status === 'selesai' ? 'bg-green-100 text-green-800' :
                                    'bg-red-100 text-red-800'))
                                }}">
                                {{ ucfirst($transaksi->status) }}
                            </span>
                        </p>
                        @if ($transaksi->resi)
                            <p><span class="font-medium">Resi:</span> {{ $transaksi->resi }}</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        @if ($transaksi->status === 'diproses')
                            <form action="{{ route('user.transaksi.show', $transaksi->id) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                <input type="text" name="resi" required placeholder="Masukkan No Resi" 
                                    class="rounded-lg border-gray-300 text-sm px-3 py-2 w-48 focus:ring-indigo-500 focus:border-indigo-500">
                                <button type="submit"
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                    Tandai Dikirim
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-12 space-y-2">
                <i data-lucide="inbox" class="mx-auto w-8 h-8"></i>
                <p class="italic">Belum ada transaksi masuk ke toko ini.</p>
            </div>
        @endforelse
    </div>

    {{-- SECTION: Daftar Produk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 pt-4">
        @forelse ($produkList as $produk)
            <div class="bg-white rounded-2xl shadow hover:shadow-md transition flex flex-col overflow-hidden border border-gray-100">
                <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                    <img src="{{ $produk->gambar ? asset('storage/' . $produk->gambar) : asset('images/default-produk.png') }}"
                         alt="Foto Produk" class="w-full h-full object-cover">
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                    <div>
                        <h2 class="font-semibold text-base text-gray-900 truncate">{{ $produk->nama }}</h2>
                        <p class="text-xs text-gray-500 line-clamp-2">{{ $produk->deskripsi }}</p>
                    </div>
                    <div class="text-indigo-600 font-bold text-lg">
                        Rp{{ number_format($produk->harga, 0, ',', '.') }}
                    </div>
                </div>
                <div class="border-t bg-gray-50 px-5 py-3">
                    <div class="flex flex-col md:flex-row justify-end gap-2">
                        <form action="{{ route('user.produk.edit', $produk->id) }}" method="GET">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                Edit
                            </button>
                        </form>

                        <form action="{{ route('user.produk.destroy', $produk->id) }}" method="POST"
                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                                <i data-lucide="trash" class="w-4 h-4"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-400 py-24 col-span-full space-y-3">
                <i data-lucide="package" class="mx-auto w-8 h-8"></i>
                <p class="italic">Belum ada produk di toko ini.</p>
            </div>
        @endforelse
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>

{{-- Alpine.js --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
