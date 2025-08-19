@extends('layouts.app')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Transaksi</h2>
            <p class="text-sm text-gray-500">Lihat riwayat pembelian produk Anda di sini.</p>
        </div>

        {{-- Tab Status --}}
        @php
            $statusAktif = request('status');
            $statusList = [
                'diproses' => ['label' => 'Diproses', 'icon' => 'Clock'],
                'dikirim' => ['label' => 'Dikirim', 'icon' => 'Truck'],
                'selesai' => ['label' => 'Selesai', 'icon' => 'Check-Circle'],
            ];
        @endphp

        {{-- Tab Status Modern & Centered --}}
        <div class="flex justify-center mb-8">
            <div class="flex flex-wrap gap-3 justify-center items-center">

                {{-- Tombol Semua Transaksi --}}
                <a href="{{ route('user.transaksi.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-semibold text-gray-500 border border-gray-200 bg-white shadow-sm
                hover:text-blue-600 hover:bg-gray-50 transition">
                    <i data-lucide="List" class="w-4 h-4"></i>
                    Semua
                </a>
                
                {{-- Tab per Status --}}
                @foreach ($statusList as $key => $status)
                    <a href="{{ route('user.transaksi.index', ['status' => $key]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full border text-sm font-semibold transition-all duration-200 shadow-sm
                    {{ $statusAktif === $key 
                        ? 'bg-blue-100 text-blue-700 border-blue-300' 
                        : 'bg-white text-gray-500 border-gray-200 hover:bg-gray-50 hover:text-blue-600' }}">
                        <i data-lucide="{{ $status['icon'] }}" class="w-4 h-4"></i>
                        {{ $status['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Jika tidak ada transaksi --}}
        @if ($transaksiList->isEmpty())
            <div class="text-center text-gray-500 py-6">
                Belum ada transaksi yang tercatat.
            </div>
        @else

            {{-- Daftar Transaksi --}}
            <div class="space-y-10">
                @foreach ($transaksiList as $transaksi)
                    @php $totalProduk = 0; @endphp
                    <div class="border border-gray-200 rounded-2xl p-6 space-y-6 shadow-sm">

                        {{-- Informasi Transaksi --}}
                        <div class="flex justify-between items-start">
                            <div>
                                <h2>Transaksi {{ $transaksi->kode_transaksi }}</h2>
                                <p class="text-sm text-gray-500">Tanggal: {{ $transaksi->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="inline-block bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full capitalize">
                                {{ $transaksi->status }}
                            </span>
                        </div>

                        {{-- Produk dalam Transaksi --}}
                        <div class="space-y-6">
                            @if ($transaksi->checkout && $transaksi->checkout->item)
                                @foreach ($transaksi->checkout->item as $item)
                                    @php
                                        $produk = $item->produk;
                                        $varian = $item->varian;
                                        $jumlah = (int) $item->jumlah;
                                        $harga = $varian->harga ?? $produk->harga ?? 0;
                                        $subtotal = $jumlah * $harga;
                                        $totalProduk += $subtotal;
                                    @endphp

                                    <div class="flex flex-col md:flex-row gap-6 border-b pb-4 last:border-b-0">
                                        <div class="w-full md:w-32 h-32 bg-gray-100 rounded-xl overflow-hidden">
                                            <img src="{{ asset('storage/' . ($varian->gambar ?? $produk->gambar ?? 'img/default.png')) }}"
                                                 alt="{{ $produk->nama ?? 'Produk' }}"
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 space-y-1">
                                            <h4 class="text-lg font-semibold">
                                                {{ $produk->nama ?? 'Produk tidak ditemukan' }}
                                                @if ($varian)
                                                    <span class="text-sm text-gray-500">({{ $varian->nama }})</span>
                                                @endif
                                            </h4>
                                            <p class="text-sm text-gray-500">Toko: {{ $produk->toko->nama_toko ?? '-' }}</p>
                                            <div class="flex justify-between text-sm text-gray-600">
                                                <span>Jumlah</span><span>{{ $jumlah }}</span>
                                            </div>
                                            <div class="flex justify-between text-sm text-gray-600">
                                                <span>Harga Satuan</span><span>Rp{{ number_format($harga, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between font-semibold text-gray-800 border-t pt-2">
                                                <span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Ringkasan Pembayaran --}}
                        <div class="bg-blue-50 rounded-xl p-4 space-y-2 text-sm border border-blue-100">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Total Produk</span>
                                <span class="font-semibold text-gray-800">Rp{{ number_format($totalProduk, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Ongkir</span>
                                <span class="font-semibold text-gray-800">Rp{{ number_format($transaksi->pengiriman->ongkir ?? 0, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Estimasi</span>
                               <p class="text-sm text-gray-600">
                                    {{ $transaksi->pengiriman->layanan }}
                                </p>
                            </div>
                            <div class="border-t pt-3 flex justify-between font-bold text-base text-blue-700">
                                <span>Total Bayar</span>
                                <span>
                                    Rp{{ number_format($transaksi->pembayaran->total ?? ($totalProduk + ($transaksi->pengiriman->ongkir ?? 0)), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Lihat Detail --}}
                        <div class="pt-2 flex justify-end">
                            <a href="{{ route('user.transaksi.show', $transaksi->id) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                                <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Lihat Detail
                            </a>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Navigasi Halaman --}}
            <div class="pt-10">
                {{ $transaksiList->links('pagination::tailwind') }}
            </div>
        @endif
    </div>
</div>

{{-- Ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>

{{-- SweetAlert Notification --}}
@if (session('success') || session('error') || session('welcome'))
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
