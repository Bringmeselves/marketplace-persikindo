@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Checkout</h2>

    {{-- Produk Checkout --}}
    @if($checkout->item && count($checkout->item))
        <div class="space-y-6">
            @foreach ($checkout->item as $item)
                <div class="bg-white shadow-lg rounded-2xl p-6 flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-40 h-40 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $item->gambar) }}" alt="Gambar Produk"
                             class="w-full h-full object-cover">
                    </div>

                    <div class="flex flex-col justify-between flex-grow space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                                @if($item->varian)
                                    <span class="text-sm text-gray-500">({{ $item->varian->nama }})</span>
                                @endif
                            </h3>

                            <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                <li class="flex justify-between">
                                    <span>Jumlah</span>
                                    <span class="font-medium text-gray-800">{{ $item->jumlah }}</span>
                                </li>
                                <li class="flex justify-between font-bold text-gray-900 border-t pt-2">
                                    <span>Subtotal</span>
                                    <span>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex flex-col md:flex-row justify-end gap-2">
                            {{-- Form Update Item --}}
                            <form action="{{ route('user.checkout.item.update', [$checkout->id, $item->id]) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="flex items-center gap-2">
                                    {{-- Dropdown Varian Produk --}}
                                    <select name="varian_id"
                                        class="min-w-[200px] border rounded-md px-2 py-1 text-sm text-gray-700">
                                        @foreach ($item->produk->varian as $varian)
                                            <option value="{{ $varian->id }}" @selected($varian->id == $item->varian_id)>
                                                {{ $varian->nama }} - Rp{{ number_format($varian->harga) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    {{-- Input Jumlah --}}
                                    <input
                                        type="number"
                                        name="jumlah"
                                        value="{{ $item->jumlah }}"
                                        min="1"
                                        class="w-20 px-2 py-1 border rounded-md text-sm text-gray-700"
                                    />

                                    {{-- Tombol Simpan --}}
                                    <button type="submit"
                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-100 text-blue-800 hover:bg-blue-200 text-sm font-semibold">
                                        <i data-lucide="save" class="w-4 h-4"></i>
                                        Simpan
                                    </button>
                                </div>
                            </form>

                            {{-- Form Hapus Item --}}
                            <form action="{{ route('user.checkout.item.destroy', [$checkout->id, $item->id]) }}" method="POST" class="form-delete-item">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 text-sm font-semibold">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-6 flex justify-between items-center text-lg font-bold text-gray-900">
            <span>Total Harga</span>
            <span class="text-indigo-600">Rp{{ number_format($checkout->total_harga, 0, ',', '.') }}</span>
        </div>
    @endif

    {{-- SECTION: Toko --}}
    <a href="{{ route('user.toko.show', $checkout->toko->id) }}" class="block">
        <div class="bg-white border rounded-2xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition">
            @if($checkout->toko->foto_toko)
                <img src="{{ asset('storage/' . $checkout->toko->foto_toko) }}" alt="Foto Toko"
                     class="w-20 h-20 object-cover rounded-full border shadow-sm">
            @else
                <div class="w-20 h-20 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full text-xs text-center">
                    Tidak ada<br>foto toko
                </div>
            @endif
            <div class="text-sm space-y-1">
                <h4 class="text-lg font-semibold text-gray-900">{{ $checkout->toko->nama_toko }}</h4>
                <p class="text-gray-600">{{ $checkout->toko->alamat }}</p>
            </div>
        </div>
    </a>

    {{-- Alamat Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="flex items-center gap-2 text-xl font-semibold text-gray-900">
                <i data-lucide="map-pin" class="w-5 h-5 text-indigo-500"></i> Alamat Pengiriman
            </h3>
            <a href="{{ $checkout->pengiriman ? route('user.pengiriman.alamat.edit', $checkout->id) : route('user.pengiriman.alamat.create', $checkout->id) }}"
               class="text-sm text-indigo-600 hover:underline">
                {{ $checkout->pengiriman ? 'Ubah Alamat' : 'Tambah Alamat' }}
            </a>
        </div>
        @if ($checkout->pengiriman)
            <div class="space-y-1 text-sm text-gray-700">
                <div class="flex justify-between"><span>Nama Penerima</span><span>{{ $checkout->pengiriman->nama_lengkap }}</span></div>
                <div class="flex justify-between"><span>Alamat</span><span class="text-right">{{ $checkout->pengiriman->alamat_penerima }}</span></div>
                <div class="flex justify-between"><span>Kota & Kode Pos</span><span>{{ $checkout->pengiriman->city_name }}, {{ $checkout->pengiriman->kode_pos }}</span></div>
                <div class="flex justify-between"><span>WA</span><span>{{ $checkout->pengiriman->nomor_wa }}</span></div>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Belum ada alamat pengiriman.</p>
        @endif
    </div>

    {{-- Jasa Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="flex items-center gap-2 text-xl font-semibold text-gray-900">
                <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i> Jasa Pengiriman
            </h3>
            @if ($checkout->pengiriman)
                <a href="{{ route('user.pengiriman.kurir.edit', $checkout->id) }}" class="text-sm text-indigo-600 hover:underline">
                    {{ $checkout->pengiriman->kurir ? 'Ubah Kurir' : 'Pilih Kurir' }}
                </a>
            @endif
        </div>
        @if ($checkout->pengiriman && $checkout->pengiriman->kurir)
            <div class="space-y-1 text-sm text-gray-700">
                <div class="flex justify-between"><span>Kurir</span><span>{{ strtoupper($checkout->pengiriman->kurir) }}</span></div>
                <div class="flex justify-between"><span>Layanan</span><span>{{ $checkout->pengiriman->layanan }}</span></div>
                <div class="flex justify-between"><span>Ongkir</span><span>Rp{{ number_format($checkout->pengiriman->ongkir, 0, ',', '.') }}</span></div>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Kurir belum dipilih.</p>
        @endif
    </div>

    {{-- Tombol Checkout --}}
    <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
        @if ($checkout->pengiriman && $checkout->pengiriman->kurir)
            <form action="{{ route('user.pembayaran.create', $checkout->id) }}" method="GET">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-5 rounded-xl shadow-md transition duration-200">
                    <i data-lucide="wallet" class="w-5 h-5"></i> Lanjut ke Pembayaran
                </button>
            </form>
        @else
            <p class="text-sm text-red-500">Silakan lengkapi alamat dan kurir terlebih dahulu.</p>
        @endif
    </div>
</div>

{{-- Lucide & SweetAlert --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();

        // Konfirmasi hapus item dengan SweetAlert
        document.querySelectorAll('.form-delete-item').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Hapus item ini?',
                    text: "Produk akan dihapus dari checkout.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const loader = document.getElementById('loader');
                        if (loader) loader.classList.remove('hidden');
                        form.requestSubmit();
                    }
                });
            });
        });

        // Sembunyikan loader jika masih tampil
        const loader = document.getElementById('loader');
        if (loader) {
            setTimeout(() => loader.classList.add('hidden'), 1000);
        }
    });
</script>
{{-- SweetAlert Notification --}}
@if (session('success') || session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#6366f1',
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#ef4444',
                });
            @endif
        });
    </script>
@endif
@endsection
