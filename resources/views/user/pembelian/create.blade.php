@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-12">
    <h2 class="text-4xl font-extrabold mb-12 text-center text-gray-900 tracking-tight">🛒 Form Pembelian Produk</h2>

    <div class="bg-white shadow-xl rounded-2xl p-10 max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- KIRI: Gambar Produk --}}
            <div class="flex justify-center items-start">
                <img id="main-image" 
                     src="{{ asset('storage/' . (isset($varian) && $varian->gambar ? $varian->gambar : $produk->gambar)) }}" 
                     alt="{{ $produk->nama }}"
                     class="rounded-xl object-cover w-full max-w-md shadow-md transition-transform hover:scale-105 duration-300" />
            </div>

            {{-- KANAN: Detail Produk dan Form --}}
            <div class="flex flex-col justify-between space-y-6">

                {{-- CARD 1: Informasi Produk --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 space-y-6 shadow-sm">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $produk->nama }}</h3>

                    <ul class="space-y-2 text-gray-800 text-md">
                        <li><strong>Harga:</strong> <span id="harga-text">Rp{{ number_format($varian->harga ?? $produk->harga, 0, ',', '.') }}</span></li>
                        <li><strong>Stok Tersedia:</strong> <span id="stok-text">{{ $varian->stok ?? $produk->stok }}</span></li>
                    </ul>

                    <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                        <h4 class="font-semibold mb-2">📄 Deskripsi Produk</h4>
                        <p>{{ $produk->deskripsi }}</p>
                    </div>

                    {{-- Pilihan Varian --}}
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">🎨 Pilih Varian:</h4>
                        <div class="flex flex-wrap gap-4">
                            @foreach ($produk->varian as $v)
                                <button type="button"
                                        class="varian-option border border-gray-300 rounded-lg p-2 hover:border-indigo-500 focus:ring-2 focus:ring-indigo-400 transition flex flex-col items-center w-28 cursor-pointer"
                                        data-id="{{ $v->id }}"
                                        data-harga="{{ $v->harga }}"
                                        data-stok="{{ $v->stok }}"
                                        data-gambar="{{ asset('storage/' . $v->gambar) }}">
                                    <img src="{{ asset('storage/' . $v->gambar) }}" alt="{{ $v->nama }}"
                                         class="w-20 h-20 object-cover rounded-md mb-2 shadow-sm">
                                    <div class="text-xs text-center">
                                        <strong>{{ $v->nama }}</strong><br>
                                        Rp{{ number_format($v->harga, 0, ',', '.') }}<br>
                                        Stok: {{ $v->stok }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- CARD 2: Informasi Toko --}}
                <a href="{{ route('user.toko.show', $produk->toko->id) }}" class="block hover:bg-gray-50 transition rounded-xl">
                    <div class="bg-white border border-gray-200 rounded-xl p-4 flex gap-4 shadow-sm items-center">
                        <img src="{{ asset('storage/' . $produk->toko->foto_toko) }}" alt="Foto Toko"
                            class="w-20 h-20 object-cover rounded-full shadow">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-800">{{ $produk->toko->nama_toko }}</h4>
                            <p class="text-sm text-gray-600">{{ $produk->toko->alamat }}</p>
                        </div>
                    </div>
                </a>

                {{-- CARD 3: Form Pembelian --}}
                <form action="{{ route('user.checkout.start') }}" method="POST" class="space-y-4" onsubmit="return validateVarian();">
                    @csrf
                    <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                    <input type="hidden" name="varian_id" id="varian_id">

                    {{-- Jumlah Pembelian --}}
                    <div>
                        <label for="jumlah" class="block text-lg font-medium text-gray-700 mb-1">🔢 Jumlah Pembelian</label>
                        <input type="number" name="jumlah" id="jumlah" min="1" value="1" required
                               class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit"
                            class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-semibold text-xl hover:bg-indigo-700 transition-shadow shadow-md hover:shadow-lg">
                        Lanjut ke Checkout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Script interaktif varian --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const varianButtons = document.querySelectorAll('.varian-option');
        const inputVarianId = document.getElementById('varian_id');
        const hargaText = document.getElementById('harga-text');
        const stokText = document.getElementById('stok-text');
        const mainImage = document.getElementById('main-image');

        varianButtons.forEach(button => {
            button.addEventListener('click', function () {
                inputVarianId.value = this.dataset.id;
                hargaText.textContent = 'Rp' + Number(this.dataset.harga).toLocaleString('id-ID');
                stokText.textContent = this.dataset.stok;
                mainImage.src = this.dataset.gambar;

                varianButtons.forEach(btn => btn.classList.remove('ring', 'ring-indigo-400'));
                this.classList.add('ring', 'ring-indigo-400');
            });
        });
    });

    // Validasi sebelum submit form
    function validateVarian() {
        const varianId = document.getElementById('varian_id').value;
        if (!varianId) {
            alert('Silakan pilih varian terlebih dahulu.');
            return false;
        }
        return true;
    }
</script>
@endsection
