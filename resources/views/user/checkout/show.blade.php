@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md mt-6">
    <h1 class="text-2xl font-semibold mb-6">Checkout Produk</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-6">{{ session('error') }}</div>
    @endif

    <form action="{{ route('user.checkout.store') }}" method="POST" class="space-y-8">
        @csrf

        {{-- Info Produk --}}
        <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-8">
            <div class="flex-shrink-0 mb-6 md:mb-0">
                <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama }}" class="w-48 h-48 object-cover rounded-lg shadow">
            </div>
            <div class="flex flex-col space-y-4 w-full max-w-md">
                <div class="flex">
                    <span class="w-28 font-semibold">Produk:</span>
                    <span class="break-words">{{ $produk->nama }}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-semibold">Jumlah:</span>
                    <span>{{ $jumlah }}</span>
                </div>
                <div class="flex">
                    <span class="w-28 font-semibold">Toko:</span>
                    <span class="break-words">{{ $produk->toko ? $produk->toko->nama_toko : 'Nama toko tidak tersedia' }}</span>
                </div>
            </div>
        </div>

        {{-- Checkout info --}}
        <div>
            <label for="nama_lengkap" class="block font-medium mb-2">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required class="w-full border border-gray-300 rounded px-4 py-2">
            @error('nama_lengkap')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="nomor_wa" class="block font-medium mb-2">Nomor WhatsApp</label>
            <input type="text" name="nomor_wa" id="nomor_wa" value="{{ old('nomor_wa') }}" required class="w-full border border-gray-300 rounded px-4 py-2">
            @error('nomor_wa')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="alamat_penerima" class="block font-medium mb-2">Alamat Penerima</label>
            <textarea name="alamat_penerima" id="alamat_penerima" rows="3" required class="w-full border border-gray-300 rounded px-4 py-2">{{ old('alamat_penerima') }}</textarea>
            @error('alamat_penerima')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Pengiriman --}}
        <h2 class="text-xl font-semibold mt-8 mb-4">Data Pengiriman</h2>
        <div>
            <label for="kurir" class="block font-medium mb-2">Kurir</label>
            <input type="text" name="kurir" id="kurir" value="{{ old('kurir') }}" required class="w-full border border-gray-300 rounded px-4 py-2">
            @error('kurir')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="layanan" class="block font-medium mb-2">Layanan</label>
            <input type="text" name="layanan" id="layanan" value="{{ old('layanan') }}" required class="w-full border border-gray-300 rounded px-4 py-2">
            @error('layanan')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Pembayaran --}}
        <h2 class="text-xl font-semibold mt-8 mb-4">Metode Pembayaran</h2>
        <select name="metode_pembayaran" required class="w-full border border-gray-300 rounded px-4 py-2">
            <option value="">-- Pilih Metode Pembayaran --</option>
            <option value="transfer_bank" {{ old('metode_pembayaran') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
            <option value="ewallet" {{ old('metode_pembayaran') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
            <option value="cod" {{ old('metode_pembayaran') == 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
        </select>
        @error('metode_pembayaran')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror

        {{-- Submit --}}
        <div class="text-right mt-6">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">Bayar Sekarang</button>
        </div>
    </form>
</div>
@endsection
