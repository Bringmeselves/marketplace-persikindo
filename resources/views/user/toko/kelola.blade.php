@extends('layouts.app')

@section('title', 'Kelola Toko')

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
            <div class="flex-1 space-y-4">

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

                {{-- Kontak dan Lokasi --}}
                <div class="space-y-1 text-sm text-gray-600">
                    <p class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4 text-gray-400"></i>
                        {{ $toko->alamat ?: '-' }}
                    </p>
                    <p class="flex items-center gap-2">
                        <i data-lucide="city" class="w-4 h-4 text-gray-400"></i>
                        {{ $toko->city_name ?? '-' }}
                    </p>
                    <p class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4 text-gray-400"></i>
                        @if($toko->nomer_wa)
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $toko->nomer_wa) }}"
                            target="_blank"
                            class="text-indigo-600 font-medium hover:underline">
                                {{ $toko->nomer_wa }}
                            </a>
                        @else
                            <span>-</span>
                        @endif
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
    </div>
    
    {{-- SECTION: Performa Penjualan Toko --}}
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
        <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

            {{-- Header --}}
            <div class="border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <i data-lucide="bar-chart-3" class="w-6 h-6 text-indigo-500"></i>
                    Performa Penjualan Toko
                </h2>
                <p class="text-sm text-gray-500">Ringkasan statistik penjualan produk dari tokomu.</p>
            </div>

            {{-- Ringkasan Penjualan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-center text-sm font-medium text-gray-700">
                <div class="bg-gray-50 rounded-xl p-4 shadow-sm border">
                    <div class="text-indigo-600 text-2xl font-bold">{{ $totalProdukTerjual }}</div>
                    <div>Produk Terjual</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 shadow-sm border">
                    <div class="text-indigo-600 text-2xl font-bold">{{ $jumlahTransaksiSelesai }}</div>
                    <div>Transaksi Selesai</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 shadow-sm border">
                    <div class="text-indigo-600 text-2xl font-bold">Rp{{ number_format($saldo, 0, ',', '.') }}</div>
                    <div>Total Pendapatan</div>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 shadow-sm border">
                    <div class="text-indigo-600 text-2xl font-bold">{{ $jumlahProduk }}</div>
                    <div>Produk Aktif</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION: Saldo Toko --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-4">
        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="wallet" class="w-5 h-5 text-indigo-500"></i>
                Saldo Toko
            </h2>
            <p class="text-sm text-gray-500">Saldo hasil penjualan produk dari toko ini.</p>
        </div>

        {{-- Saldo dan Tombol Aksi --}}
        <div class="flex items-center justify-between flex-wrap gap-4">

            {{-- Saldo --}}
            <div class="text-3xl font-bold text-gray-800">
                Rp{{ number_format($toko->saldo ?? 0, 0, ',', '.') }}
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex items-center gap-3">
                {{-- Tombol Riwayat Penarikan --}}
                <a href="{{ route('user.penarikan.index') }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium py-2 px-4 rounded-xl transition shadow-sm"
                    title="Riwayat Penarikan">
                    <i data-lucide="clock" class="w-4 h-4"></i> Riwayat
                </a>

                {{-- Tombol Tarik Uang --}}
                <a href="{{ route('user.penarikan.create') }}"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium py-2 px-4 rounded-xl transition shadow"
                    title="Tarik Uang">
                    <i data-lucide="wallet" class="w-4 h-4"></i> Tarik Uang
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION: Aksi --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-4">
        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="package-plus" class="w-5 h-5 text-indigo-500"></i>
                Aksi Toko
            </h2>
            <p class="text-sm text-gray-500">Lakukan tindakan untuk menambah produk atau mengelola toko.</p>
        </div>

       {{-- Tombol Aksi --}}
        <div class="flex justify-end gap-4">
            {{-- Tombol Tambah Produk --}}
            <a href="{{ route('user.produk.create', ['toko_id' => $toko->id]) }}"
            class="w-12 h-12 flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition shadow"
            title="Tambah Produk">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </a>
        </div>
    </div>

    {{-- SECTION: Transaksi Masuk --}}
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
        <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

            {{-- Header --}}
            <div class="border-b pb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-indigo-500"></i>
                        Transaksi Masuk
                    </h2>
                    <p class="text-sm text-gray-500">Daftar transaksi dari pembeli yang masuk ke tokomu.</p>
                </div>
                
                {{-- Tombol Riwayat --}}
                <a href="{{ route('user.toko.riwayat', ['id' => $toko->id]) }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition">
                    <i data-lucide="history" class="w-4 h-4 mr-2"></i>
                    Riwayat Transaksi
                </a>
            </div>

            {{-- Daftar Transaksi --}}
            @if ($transaksiMasuk->isEmpty())
                <div class="text-center text-gray-500 py-6">
                    Belum ada transaksi masuk ke toko ini.
                </div>
            @else
                <div class="space-y-10">
                    @foreach ($transaksiMasuk as $transaksi)
                        @php
                            $item = $transaksi->checkout->item->first();
                            $produk = $item->produk ?? null;
                            $varian = $item->varian ?? null;
                            $jumlah = $item->jumlah ?? 0;
                            $harga = $varian->harga ?? $produk->harga ?? 0;
                            $subtotal = $jumlah * $harga;
                            $ongkir = $transaksi->pengiriman->ongkir ?? 0;
                            $total = $transaksi->pembayaran->total ?? ($subtotal + $ongkir);
                            $pengiriman = $transaksi->pengiriman ?? null;
                        @endphp

                        <div class="border border-gray-200 rounded-2xl p-6 space-y-6 shadow-sm">

                            {{-- Informasi Transaksi --}}
                            <div class="flex justify-between items-start border-b pb-4">
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">Transaksi #{{ $transaksi->kode_transaksi }}</h3>
                                    <p class="text-sm text-gray-500">Tanggal: {{ $transaksi->created_at->format('d M Y H:i') }}</p>
                                </div>
                                <span class="inline-block text-sm font-medium px-3 py-1 rounded-full capitalize
                                    {{
                                        $transaksi->status === 'diproses' ? 'bg-yellow-100 text-yellow-800' :
                                        ($transaksi->status === 'dikirim' ? 'bg-blue-100 text-blue-800' :
                                        ($transaksi->status === 'selesai' ? 'bg-green-100 text-green-800' :
                                        'bg-red-100 text-red-800'))
                                    }}">
                                    {{ $transaksi->status }}
                                </span>
                            </div>

                            {{-- Produk --}}
                            @php
                                $totalProduk = 0; 
                            @endphp

                            @foreach ($transaksi->checkout->item as $item)
                                @php
                                    $produk = $item->produk ?? null;
                                    $varian = $item->varian ?? null;
                                    $jumlah = $item->jumlah ?? 0;
                                    $harga = $varian->harga ?? $produk->harga ?? 0;
                                    $subtotal = $jumlah * $harga;
                                    $totalProduk += $subtotal;
                                @endphp

                                <div class="flex flex-col md:flex-row gap-6 border-b pb-4 last:border-0">
                                    <div class="w-full md:w-32 h-32 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center">
                                        <img src="{{ asset('storage/' . ($varian->gambar ?? $produk->gambar ?? 'img/default.png')) }}"
                                            alt="{{ $produk->nama ?? 'Produk' }}"
                                            class="object-cover w-full h-full">
                                    </div>
                                    <div class="flex-grow space-y-2 text-sm text-gray-700">
                                        <h4 class="text-base font-semibold text-gray-800">Detail Produk</h4>
                                        <div class="flex justify-between"><span>Nama Produk</span><span class="font-medium">{{ $produk->nama ?? '-' }}</span></div>
                                        @if ($varian)
                                            <div class="flex justify-between"><span>Varian</span><span>{{ $varian->nama }}</span></div>
                                        @endif
                                        <div class="flex justify-between"><span>Jumlah</span><span>{{ $jumlah }}</span></div>
                                        <div class="flex justify-between"><span>Harga</span><span>Rp{{ number_format($harga, 0, ',', '.') }}</span></div>
                                        <div class="flex justify-between"><span>Subtotal</span><span>Rp{{ number_format($subtotal, 0, ',', '.') }}</span></div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Total Pembayaran --}}
                            @php
                                $ongkir = $transaksi->pengiriman->ongkir ?? 0;
                                $totalAkhir = $totalProduk + $ongkir;
                            @endphp

                            <div class="text-sm text-gray-700 border-t pt-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-800">Total Harga Produk</span>
                                    <span class="font-medium">Rp{{ number_format($totalProduk, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-800">Ongkos Kirim</span>
                                    <span class="font-medium">Rp{{ number_format($ongkir, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-lg font-bold text-gray-900 border-t pt-3">
                                    <span>Total Pembayaran</span>
                                    <span>Rp{{ number_format($totalAkhir, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Informasi Pengiriman --}}
                            @if ($pengiriman)
                                <div class="space-y-2 text-sm text-gray-700 border-t pt-4">
                                    <h4 class="text-base font-semibold text-gray-800">Informasi Pengiriman</h4>
                                    <div class="flex justify-between"><span>Kurir</span><span>{{ strtoupper($pengiriman->kurir) }}</span></div>
                                    <div class="flex justify-between"><span>Layanan</span><span>{{ $pengiriman->layanan }}</span></div>
                                    <div class="flex justify-between"><span>Ongkir</span><span>Rp{{ number_format($pengiriman->ongkir, 0, ',', '.') }}</span></div>
                                    <div class="flex justify-between">
                                        <span>Nomor Resi</span>
                                        <span class="{{ $transaksi->resi ? 'text-green-700 font-mono' : 'text-gray-400 italic' }}">
                                            {{ $transaksi->resi ?? 'Belum diinput' }}
                                        </span>
                                    </div>

                                    {{-- Alamat Penerima --}}
                                    <div class="pt-4 space-y-1 border-t">
                                        <h5 class="font-semibold text-gray-800">Alamat Penerima</h5>
                                        <div class="flex justify-between"><span>Nama</span><span>{{ $pengiriman->nama_lengkap }}</span></div>
                                        <div class="flex justify-between"><span>Alamat</span><span class="text-right">{{ $pengiriman->alamat_penerima }}</span></div>
                                        <div class="flex justify-between"><span>Kota & Kode Pos</span><span>{{ $pengiriman->city_name }}, {{ $pengiriman->kode_pos }}</span></div>
                                        <div class="flex justify-between"><span>WA</span><span>{{ $pengiriman->nomor_wa }}</span></div>
                                    </div>
                                </div>
                            @endif

                            {{-- Form Input Resi --}}
                            @if ($transaksi->status === 'diproses' && !$transaksi->resi)
                                <div class="pt-4 border-t">
                                    <form action="{{ route('user.transaksi.inputResi', $transaksi->id) }}" method="POST"
                                        class="flex flex-col sm:flex-row sm:items-center gap-2 mt-4">
                                        @csrf
                                        <input type="text" name="resi" required placeholder="Masukkan No Resi"
                                            class="rounded-lg border-gray-300 text-sm px-3 py-2 w-full sm:w-64 focus:ring-indigo-500 focus:border-indigo-500"
                                            value="{{ old('resi') }}">
                                        <button type="submit"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                            Tandai Dikirim
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="pt-8">
                    {{ $transaksiMasuk->links() }}
                </div>
            @endif
        </div>
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
                    <div class="text-sm text-gray-600">
                        Stok: {{ $produk->stok }}
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
