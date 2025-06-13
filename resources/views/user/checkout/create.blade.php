@extends('layouts.app')

@section('title', 'Checkout Produk')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 space-y-8">

    <h2 class="text-3xl font-bold text-gray-800">🛒 Checkout Produk</h2>

    {{-- ============================ DETAIL PRODUK ============================ --}}
    <div class="bg-white shadow-md rounded-2xl p-6">
        <h3 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-5">📦 Detail Produk</h3>

        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            {{-- Gambar --}}
            <div class="md:w-1/3 flex justify-center md:justify-start">
                <div class="w-40 h-40 md:w-48 md:h-48 rounded-xl overflow-hidden shadow">
                    <img 
                        src="{{ asset('storage/' . ($checkout->gambar ?? $checkout->varian->gambar ?? $checkout->produk->gambar)) }}" 
                        alt="{{ $checkout->produk->nama }}" 
                        class="w-full h-full object-cover"
                    >
                </div>
            </div>

            {{-- Info produk --}}
            <div class="md:w-2/3 space-y-2 text-gray-700">
                <p><strong>🛍️ Nama Produk:</strong> {{ $checkout->produk->nama }}</p>
                <p><strong>🎨 Varian:</strong> {{ $checkout->varian->nama }}</p>
                <p><strong>💰 Harga Satuan:</strong> Rp{{ number_format($checkout->harga_satuan) }}</p>
                <p><strong>🔢 Jumlah:</strong> {{ $checkout->jumlah }}</p>
                <p><strong>💸 Total Harga:</strong> 
                    <span class="text-green-600 font-semibold">Rp{{ number_format($checkout->total_harga) }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- ============================ TOKO PENGIRIM ============================ --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 flex gap-4 shadow-sm items-center">
            <img src="{{ asset('storage/' . $produk->toko->foto_toko) }}" alt="Foto Toko"
            class="w-20 h-20 object-cover rounded-full shadow">
        <div>
            <h4 class="text-lg font-semibold text-gray-800">{{ $produk->toko->nama_toko }}</h4>
            <p class="text-sm text-gray-600">{{ $produk->toko->alamat }}</p>
        </div>
    </div>

    {{-- ============================ FORMULIR PENGIRIMAN ============================ --}}
    <div class="bg-white shadow-md rounded-2xl p-6">
        <h3 class="text-xl font-semibold text-gray-700 border-b pb-3 mb-5">🚚 Informasi Pengiriman</h3>

        <form action="{{ route('user.pengiriman.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Hidden Inputs --}}
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">
            <input type="hidden" name="varian_id" value="{{ $varian->id }}">
            <input type="hidden" name="jumlah" value="{{ $jumlah }}">

            @php
                $fields = [
                    ['label' => 'Nama Lengkap', 'name' => 'nama_lengkap', 'type' => 'text', 'required' => true],
                    ['label' => 'Nomor WhatsApp', 'name' => 'nomor_wa', 'type' => 'text', 'required' => true],
                    ['label' => 'Alamat Penerima', 'name' => 'alamat_penerima', 'type' => 'textarea', 'required' => true],
                    ['label' => 'Kota', 'name' => 'cities', 'type' => 'text'],
                    ['label' => 'Kode Pos', 'name' => 'kode_pos', 'type' => 'text'],
                    ['label' => 'Catatan (opsional)', 'name' => 'catatan', 'type' => 'textarea'],
                    ['label' => 'Kurir (opsional)', 'name' => 'kurir', 'type' => 'text'],
                    ['label' => 'Layanan (opsional)', 'name' => 'layanan', 'type' => 'text'],
                    ['label' => 'Ongkir (opsional)', 'name' => 'ongkir', 'type' => 'number'],
                ];
            @endphp

            @foreach ($fields as $field)
                <div>
                    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-600 mb-1">
                        {{ $field['label'] }}
                    </label>

                    @if ($field['type'] === 'textarea')
                        <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="3"
                            class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500"
                            @if(!empty($field['required'])) required @endif></textarea>
                    @else
                        <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                            class="w-full border rounded-md p-2 focus:ring-2 focus:ring-blue-500"
                            @if(!empty($field['required'])) required @endif>
                    @endif
                </div>
            @endforeach

            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-semibold text-xl hover:bg-indigo-700 transition-shadow shadow-md hover:shadow-lg">
                    Konfirmasi & Bayar
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
