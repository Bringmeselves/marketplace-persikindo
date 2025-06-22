@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">
        Detail Transaksi #{{ $transaksi->id }}
    </h2>

    {{-- STATUS TRANSAKSI --}}
    <div class="bg-white shadow rounded-2xl p-6 space-y-2">
        <h3 class="text-xl font-semibold">Status Transaksi</h3>
        <span class="inline-block bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full capitalize w-fit">
            {{ $transaksi->status }}
        </span>
        <p class="text-sm text-gray-500">Tanggal: {{ $transaksi->created_at->format('d M Y H:i') }}</p>
    </div>

    {{-- RINCIAN PRODUK --}}
    <div class="bg-white shadow rounded-2xl p-6 space-y-4">
        <h3 class="text-xl font-semibold">Rincian Produk</h3>
        @php $total = 0; @endphp
        @foreach ($transaksi->checkout->item as $item)
            @php $total += $item->total_harga; @endphp
            <div class="flex flex-col md:flex-row gap-6 border-b pb-4">
                <div class="w-full md:w-40 h-40 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                    <img src="{{ asset('storage/' . ($item->varian->gambar ?? $item->gambar ?? 'placeholder.png')) }}"
                         class="w-full h-full object-cover" alt="Gambar Produk">
                </div>
                <div class="flex-grow space-y-2">
                    <h4 class="text-lg font-semibold">
                        {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                        @if($item->varian)
                            <span class="text-sm text-gray-500">({{ $item->varian->nama }})</span>
                        @endif
                    </h4>
                    <p class="text-sm text-gray-500">Toko: {{ $item->produk->toko->nama_toko ?? '-' }}</p>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Jumlah</span><span>{{ $item->jumlah }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-gray-900 border-t pt-2">
                        <span>Harga Satuan</span>
                        <span>Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        {{-- Total harga --}}
        <div class="flex justify-between text-lg font-bold pt-4 border-t">
            <span>Total Harga Produk</span>
            <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- INFORMASI PENGIRIMAN --}}
    <div class="bg-white shadow rounded-2xl p-6 space-y-4">
        <h3 class="text-xl font-semibold flex items-center gap-2">
            <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i> Informasi Pengiriman
        </h3>
        @php $pengiriman = $transaksi->checkout->pengiriman; @endphp
        @if($pengiriman)
            <div class="space-y-2 text-sm text-gray-700">
                <div class="flex justify-between"><span>Nama Penerima</span><span>{{ $pengiriman->nama_lengkap }}</span></div>
                <div class="flex justify-between"><span>Telepon</span><span>{{ $pengiriman->nomor_wa }}</span></div>
                <div class="flex justify-between"><span>Alamat</span><span class="text-right">{{ $pengiriman->alamat_penerima }}</span></div>
                <div class="flex justify-between"><span>Kota</span><span>{{ $pengiriman->cities }}</span></div>
                <div class="flex justify-between"><span>Kode Pos</span><span>{{ $pengiriman->kode_pos }}</span></div>
            </div>
            <div class="pt-4 border-t mt-4 space-y-1 text-sm text-gray-700">
                <h4 class="font-semibold text-gray-900 mb-1">Jasa Pengiriman</h4>
                <div class="flex justify-between"><span>Kurir</span><span>{{ strtoupper($pengiriman->kurir) }}</span></div>
                <div class="flex justify-between"><span>Layanan</span><span>{{ $pengiriman->layanan }}</span></div>
                <div class="flex justify-between"><span>Ongkir</span><span>Rp{{ number_format($pengiriman->ongkir, 0, ',', '.') }}</span></div>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Belum ada data pengiriman.</p>
        @endif
    </div>

    {{-- INFORMASI PEMBAYARAN --}}
    <div class="bg-white shadow rounded-2xl p-6 space-y-4 text-gray-900">
        <h3 class="text-xl font-semibold">Informasi Pembayaran</h3>
        @php $pembayaran = $transaksi->checkout->pembayaran; @endphp
        @if ($pembayaran)
            <div class="flex justify-between text-sm">
                <span class="text-gray-700">Metode</span>
                <span>{{ strtoupper($pembayaran->metode_pembayaran) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-700">Status</span>
                <span class="capitalize inline-block px-2 py-1 text-xs bg-green-100 text-green-600 rounded-full">
                    {{ $pembayaran->status_pembayaran }}
                </span>
            </div>
            <div class="flex justify-between mt-2 text-lg font-bold border-t pt-4">
                <span>Total Bayar</span>
                <span class="text-indigo-600">Rp{{ number_format($pembayaran->total_bayar, 0, ',', '.') }}</span>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Belum ada data pembayaran.</p>
        @endif
    </div>

    {{-- Tombol Kembali --}}
    <form action="{{ route('user.transaksi.index') }}" method="GET" class="w-fit">
        <button type="submit"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-sm font-semibold w-fit">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar transaksi
        </button>
    </form>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>
@endsection
