@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2>Detail Transaksi {{ $transaksi->kode_transaksi }}</h2>
            <p class="text-sm text-gray-500">Lihat status dan rincian transaksi Anda.</p>
        </div>

        {{-- Status Transaksi --}}
        <div class="space-y-2">
            <h3 class="text-xl font-semibold">Status Transaksi</h3>
            <span class="inline-block bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full capitalize w-fit">
                {{ $transaksi->status }}
            </span>
            <p class="text-sm text-gray-500">Tanggal: {{ $transaksi->created_at->format('d M Y H:i') }}</p>

            @if ($transaksi->status === 'dikirim')
                <form action="{{ route('user.transaksi.selesai', $transaksi->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 text-green-800 hover:bg-green-200 text-sm font-semibold">
                        <i data-lucide="check-circle" class="w-4 h-4"></i> Tandai Selesai
                    </button>
                </form>
            @endif

            @if ($transaksi->status === 'dikirim')
                <form action="{{ route('user.chat.ajukanKeluhan', $transaksi->id) }}" method="POST" class="mt-2">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 text-red-800 hover:bg-red-200 text-sm font-semibold">
                        <i data-lucide="message-circle-warning" class="w-4 h-4"></i> Ajukan Keluhan ke Toko
                    </button>
                </form>
            @endif
        </div>

        {{-- Rincian Produk --}}
        <div class="space-y-4">
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

                       @if ($transaksi->status === 'selesai')
                            @php
                                $sudahNilai = \App\Models\Penilaian::where('produk_id', $item->produk_id)
                                    ->where('user_id', auth()->id())
                                    ->exists();

                                $sudahNilaiToko = \App\Models\PenilaianToko::where('toko_id', $item->produk->toko->id)
                                    ->where('user_id', auth()->id())
                                    ->exists();
                            @endphp

                            {{-- Penilaian Produk --}}
                            @if (!$sudahNilai)
                                <a href="{{ route('user.penilaian.create', ['produk' => $item->produk->id]) }}"
                                    class="inline-flex items-center mt-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold">
                                    Beri Penilaian Produk
                                </a>
                            @else
                                <p class="text-sm text-green-600 mt-2">✔ Anda sudah memberi penilaian produk.</p>
                            @endif

                            {{-- Penilaian Toko --}}
                            @if (!$sudahNilaiToko)
                                <a href="{{ route('user.penilaian-toko.create', ['toko' => $item->produk->toko->id]) }}"
                                    class="inline-flex items-center mt-2 px-4 py-2 rounded-xl bg-purple-600 text-white hover:bg-purple-700 text-sm font-semibold">
                                    Beri Penilaian Toko
                                </a>
                            @else
                                <p class="text-sm text-green-600 mt-2">✔ Anda sudah memberi penilaian toko.</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach

            <div class="flex justify-between text-lg font-bold pt-4 border-t">
                <span>Total Harga Produk</span>
                <span>Rp{{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Informasi Pengiriman --}}
        @if ($transaksi->pengiriman)
            @php $pengiriman = $transaksi->pengiriman; @endphp
            <div class="space-y-4">
                <h3 class="text-xl font-semibold">Jasa Pengiriman</h3>
                <div class="flex justify-between"><span>Kurir</span><span>{{ strtoupper($pengiriman->kurir) }}</span></div>
                <div class="flex justify-between"><span>Layanan</span><span>{{ $pengiriman->layanan }}</span></div>
                <div class="flex justify-between"><span>Ongkir</span><span>Rp{{ number_format($pengiriman->ongkir, 0, ',', '.') }}</span></div>

                {{-- Tambahkan Estimasi --}}
                @if ($pengiriman->etd_runtime)
                    <div class="flex justify-between">
                        <span>Estimasi</span>
                        <span>{{ $pengiriman->etd_runtime }} hari</span>
                    </div>
                @endif
                
                <div class="flex justify-between text-sm">
                    <span class="text-gray-700 font-medium">Nomor Resi</span>
                    <span class="{{ $transaksi->resi ? 'text-green-700 font-mono' : 'text-gray-400 italic' }}">
                        {{ $transaksi->resi ?? 'Belum diinput' }}
                    </span>
                </div>

                <div class="mt-6 border-t pt-4 space-y-1 text-sm text-gray-700">
                    <h4 class="text-base font-semibold text-gray-900">Alamat Pengiriman</h4>
                    <div class="flex justify-between"><span>Nama Penerima</span><span>{{ $pengiriman->nama_lengkap }}</span></div>
                    <div class="flex justify-between"><span>Alamat</span><span class="text-right">{{ $pengiriman->alamat_penerima }}</span></div>
                    <div class="flex justify-between"><span>Kota & Kode Pos</span><span>{{ $pengiriman->city_name }}, {{ $pengiriman->kode_pos }}</span></div>
                    <div class="flex justify-between"><span>No. WA</span><span>{{ $pengiriman->nomor_wa }}</span></div>
                </div>
            </div>
        @endif

        {{-- Informasi Pembayaran --}}
        <div class="space-y-4 text-gray-900">
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
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-sm font-semibold">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke daftar transaksi
            </button>
        </form>
    </div>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            lucide.createIcons();
        });
    </script>
</div>
@endsection
