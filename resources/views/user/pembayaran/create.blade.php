@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-6">Pembayaran</h1>

    <div class="mb-6">
        <p><strong>Produk:</strong> {{ $checkout->produk->nama }}</p>
        <p><strong>Jumlah:</strong> {{ $checkout->jumlah }}</p>
        <p><strong>Total Harga:</strong> Rp{{ number_format($checkout->total_harga, 0, ',', '.') }}</p>
    </div>

    <form action="{{ route('user.pembayaran.store', $checkout->id) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
            <select name="metode_pembayaran" id="metode_pembayaran" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                <option value="">-- Pilih Metode Pembayaran --</option>
                <option value="Transfer Bank">Transfer Bank</option>
                <option value="COD">Bayar di Tempat (COD)</option>
                <option value="E-Wallet">E-Wallet</option>
            </select>
            @error('metode_pembayaran')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
            Konfirmasi Pembayaran
        </button>
    </form>
</div>
@endsection
