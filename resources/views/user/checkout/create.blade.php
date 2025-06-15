@extends('layouts.app')

@section('title', 'Checkout Produk')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6 space-y-8">

    <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
        <span class="text-indigo-600">🛒</span> Checkout Produk
    </h2>

    {{-- ============================ DETAIL PRODUK ============================ --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
        <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-5 flex items-center gap-2">
            <span class="text-yellow-500">📦</span> Detail Produk
        </h3>

        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            {{-- Gambar --}}
            <div class="md:w-1/3 flex justify-center md:justify-start">
                <div class="w-40 h-40 md:w-48 md:h-48 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                    <img 
                        src="{{ asset('storage/' . ($checkout->gambar ?? $checkout->varian->gambar ?? $checkout->produk->gambar)) }}" 
                        alt="{{ $checkout->produk->nama }}" 
                        class="w-full h-full object-cover transition-transform duration-200 hover:scale-105"
                    >
                </div>
            </div>

            {{-- Info produk --}}
            <div class="md:w-2/3 space-y-3 text-gray-700 text-sm md:text-base">
                <p><strong class="text-gray-800">🛍️ Nama Produk:</strong> {{ $checkout->produk->nama }}</p>
                <p><strong class="text-gray-800">🎨 Varian:</strong> {{ $checkout->varian->nama }}</p>
                <p><strong class="text-gray-800">💰 Harga Satuan:</strong> Rp{{ number_format($checkout->harga_satuan) }}</p>
                <p><strong class="text-gray-800">🔢 Jumlah:</strong> {{ $checkout->jumlah }}</p>
                <p><strong class="text-gray-800">💸 Total Harga:</strong> 
                    <span class="text-green-600 font-bold text-lg">Rp{{ number_format($checkout->total_harga) }}</span>
                </p>
            </div>
        </div>
    </div>

    {{-- ============================ TOKO PENGIRIM ============================ --}}
    <div class="bg-white border border-gray-200 rounded-xl p-4 flex gap-4 shadow-sm items-center hover:shadow-md transition">
        <img src="{{ asset('storage/' . $checkout->produk->toko->foto_toko) }}" alt="Foto Toko"
            class="w-20 h-20 object-cover rounded-full shadow-md border border-gray-100">
        <div>
            <h4 class="text-lg font-semibold text-gray-900">{{ $checkout->produk->toko->nama_toko }}</h4>
            <p class="text-sm text-gray-500">{{ $checkout->produk->toko->alamat }}</p>
        </div>
    </div>

    {{-- ============================ FORMULIR PENGIRIMAN ============================ --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 transition hover:shadow-xl">
        <h3 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-5 flex items-center gap-2">
            <span class="text-blue-500">🚚</span> Informasi Pengiriman
        </h3>

        <form action="{{ route('user.checkout.store', $checkout->id) }}" method="POST" class="space-y-5">
            @csrf

            @php
                $fields = [
                    ['label' => 'Nama Lengkap', 'name' => 'nama_lengkap', 'type' => 'text', 'required' => true],
                    ['label' => 'Nomor WhatsApp', 'name' => 'nomor_wa', 'type' => 'text', 'required' => true],
                    ['label' => 'Alamat Pengiriman', 'name' => 'alamat_pengiriman', 'type' => 'textarea', 'required' => true],
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
                    <label for="{{ $field['name'] }}" class="block text-sm font-semibold text-gray-700 mb-1">
                        {{ $field['label'] }}
                    </label>

                    @if ($field['type'] === 'textarea')
                        <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="3"
                            class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                            @if(!empty($field['required'])) required @endif>{{ old($field['name']) }}</textarea>
                    @else
                        <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}"
                            class="w-full border border-gray-300 rounded-md p-3 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition"
                            value="{{ old($field['name']) }}"
                            @if(!empty($field['required'])) required @endif>
                    @endif
                </div>
            @endforeach

            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-4 rounded-2xl font-semibold text-xl shadow-md hover:shadow-lg transition">
                    ✅ Konfirmasi & Bayar
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
