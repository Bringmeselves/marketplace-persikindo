@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Checkout</h2>

    {{-- Notifikasi --}}
    @if(session('success') || session('info'))
        <div class="space-y-2">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-blue-500 bg-blue-50 rounded shadow-sm">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    <span class="text-sm text-blue-800 font-medium">{{ session('info') }}</span>
                </div>
            @endif
        </div>
    @endif

    {{-- Tambah Produk --}}
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-xl p-6 space-y-8 border border-gray-100">

            {{-- Header --}}
            <div class="border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                    <i data-lucide="plus-circle" class="w-6 h-6 text-indigo-500"></i>
                    Tambah Produk
                </h2>
                <p class="text-sm text-gray-500">Pilih varian produk dan jumlah yang ingin ditambahkan ke checkout.</p>
            </div>

            {{-- Form Tambah Produk --}}
            <form action="{{ route('user.checkout.start') }}" method="POST" class="grid md:grid-cols-3 gap-6 gap-y-8">
                @csrf
                <input type="hidden" name="produk_id" id="input-produk-id">

                {{-- Pilih Varian --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Varian</label>
                    <div class="relative">
                        <select id="select-varian" name="varian_id" required
                            class="tom-select w-full rounded-xl border border-gray-300 bg-white shadow-inner focus:ring-2 focus:ring-indigo-400 pr-10 transition duration-200">
                            @foreach ($checkout->toko->produk as $produk)
                                @foreach ($produk->varian as $varian)
                                    <option value="{{ $varian->id }}" data-produk-id="{{ $produk->id }}">
                                        {{ $produk->nama }} - {{ $varian->nama }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        <i id="dropdown-icon"
                        data-lucide="chevron-down"
                        class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500 cursor-pointer z-10"></i>
                    </div>
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                    <input type="number" name="jumlah" value="1" min="1" required
                        class="w-full rounded-xl border border-gray-300 bg-white shadow-inner focus:ring-2 focus:ring-indigo-400 transition duration-200">
                </div>

                {{-- Tombol Submit --}}
                <div class="md:col-span-3 text-right mt-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-200">
                        <i data-lucide="plus" class="w-5 h-5"></i> Tambah ke Checkout
                    </button>
                </div>
            </form>

        </div>
    </div>

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
                            <form action="{{ route('user.checkout.item.edit', [$checkout->id, $item->id]) }}" method="GET">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-sm font-semibold">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>Edit
                                </button>
                            </form>

                            <form action="{{ route('user.checkout.item.destroy', [$checkout->id, $item->id]) }}" method="POST"
                                  class="form-delete-item">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 text-sm font-semibold">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>Hapus
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
    <a href="{{ route('user.toko.show', $produk->toko->id) }}" class="block">
        <div class="bg-white border rounded-2xl p-6 flex items-center gap-6 shadow-sm hover:shadow-md transition">
            @if($produk->toko->foto_toko)
                <img src="{{ asset('storage/' . $produk->toko->foto_toko) }}" alt="Foto Toko"
                     class="w-20 h-20 object-cover rounded-full border shadow-sm">
            @else
                <div class="w-20 h-20 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full text-xs text-center">
                    Tidak ada<br>foto toko
                </div>
            @endif
            <div class="text-sm space-y-1">
                <h4 class="text-lg font-semibold text-gray-900">{{ $produk->toko->nama_toko }}</h4>
                <p class="text-gray-600">{{ $produk->toko->alamat }}</p>
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

{{-- Lucide & Tom Select --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Lucide & Tom Select --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet" />

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ✅ Inisialisasi ikon lucide
        lucide.createIcons();

        // ✅ Inisialisasi TomSelect dan input produk
        const selectVarian = document.getElementById('select-varian');
        const inputProdukId = document.getElementById('input-produk-id');
        const dropdownIcon = document.getElementById('dropdown-icon');

        if (selectVarian && inputProdukId) {
            const tomSelectInstance = new TomSelect(selectVarian, {
                placeholder: 'Pilih produk dan varian',
                maxItems: 1,
                create: false,
                controlInput: null
            });

            // ✅ Update produk_id saat varian berubah
            selectVarian.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const produkId = selected.getAttribute('data-produk-id');
                inputProdukId.value = produkId;
            });

            // ✅ Isi produk_id jika sudah ada nilai awal
            if (selectVarian.selectedIndex >= 0) {
                const selected = selectVarian.options[selectVarian.selectedIndex];
                inputProdukId.value = selected.getAttribute('data-produk-id');
            }

            // ✅ Buka dropdown saat ikon diklik
            if (dropdownIcon) {
                dropdownIcon.addEventListener('click', function () {
                    tomSelectInstance.open();
                });
            }
        }

        // ✅ Konfirmasi hapus item dengan SweetAlert
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
                        // ✅ Tambahkan logika matikan loader di sini jika kamu pakai loader
                        const loader = document.getElementById('loader');
                        if (loader) loader.classList.remove('hidden');

                        form.requestSubmit();
                    }
                });
            });
        });

        // ✅ Sembunyikan loader jika masih tampil setelah alert
        // (misalnya loader masih kelihatan saat kembali dari halaman sebelumnya)
        const loader = document.getElementById('loader');
        if (loader) {
            setTimeout(() => loader.classList.add('hidden'), 1000);
        }
    });
</script>
@endsection
