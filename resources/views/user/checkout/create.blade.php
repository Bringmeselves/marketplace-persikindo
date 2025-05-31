@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-3xl p-6 bg-white shadow rounded-xl">

    <h2 class="text-2xl font-bold mb-4">Checkout Produk</h2>

    <!-- Informasi Produk -->
    <div class="mb-6">
        <h3 class="text-xl font-semibold">Detail Produk</h3>
        <div class="flex items-center space-x-4 mt-2">
            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="w-24 h-24 object-cover rounded-lg">
            <div>
                <p><strong>Nama Produk:</strong> {{ $produk->nama }}</p>
                <p><strong>Harga Satuan:</strong> Rp{{ number_format($produk->harga) }}</p>
                <p><strong>Jumlah:</strong> {{ $jumlah }}</p>
                <p><strong>Total Harga:</strong> <span class="text-red-600 font-semibold">Rp{{ number_format($produk->harga * $jumlah) }}</span></p>
                <p><strong>Nama Toko:</strong> {{ $produk->toko->nama_toko }}</p>
            </div>
        </div>
    </div>

    <!-- Formulir Pengiriman -->
    <form action="{{ route('user.pengiriman.store') }}" method="POST" class="space-y-4">
        @csrf

        <h3 class="text-xl font-semibold mb-2">Informasi Pengiriman</h3>

        <div>
            <label for="nama_lengkap" class="block font-medium">Nama Lengkap</label>
            <input type="text" id="nama_lengkap" name="nama_lengkap" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label for="nomor_wa" class="block font-medium">Nomor WhatsApp</label>
            <input type="text" id="nomor_wa" name="nomor_wa" class="w-full border p-2 rounded" required>
        </div>

        <div>
            <label for="alamat_penerima" class="block font-medium">Alamat Penerima</label>
            <textarea id="alamat_penerima" name="alamat_penerima" rows="3" class="w-full border p-2 rounded" required></textarea>
        </div>

        <div>
            <label for="cities" class="block font-medium">Kota</label>
            <input type="text" id="cities" name="cities" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="kode_pos" class="block font-medium">Kode Pos</label>
            <input type="text" id="kode_pos" name="kode_pos" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="catatan" class="block font-medium">Catatan (opsional)</label>
            <textarea id="catatan" name="catatan" rows="2" class="w-full border p-2 rounded"></textarea>
        </div>

        <div>
            <label for="kurir" class="block font-medium">Kurir (opsional)</label>
            <input type="text" id="kurir" name="kurir" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="layanan" class="block font-medium">Layanan (opsional)</label>
            <input type="text" id="layanan" name="layanan" class="w-full border p-2 rounded">
        </div>

        <div>
            <label for="ongkir" class="block font-medium">Ongkir (opsional)</label>
            <input type="number" id="ongkir" name="ongkir" class="w-full border p-2 rounded">
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Konfirmasi & Bayar
            </button>
        </div>
    </form>

</div>
@endsection
