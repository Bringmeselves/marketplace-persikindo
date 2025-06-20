@extends('layouts.app')

@section('title', 'Form Pembelian Produk')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    {{-- Judul Halaman --}}
    <div class="flex items-center gap-3">
        <i data-lucide="shopping-cart" class="w-6 h-6 text-indigo-600"></i>
        <h2 class="text-3xl font-bold text-gray-900">Form Pembelian Produk</h2>
    </div>

    {{-- SECTION: Produk --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 space-y-6">
        <div class="grid md:grid-cols-3 gap-6 items-start">
            {{-- Gambar --}}
            <div class="md:col-span-1 flex justify-center">
                <img id="main-image"
                    src="{{ asset('storage/' . (isset($varian) && $varian->gambar ? $varian->gambar : $produk->gambar)) }}"
                    alt="{{ $produk->nama }}"
                    class="w-40 h-40 object-cover rounded-xl shadow">
            </div>

            {{-- Info Produk --}}
            <div class="md:col-span-2 space-y-3 text-base">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama Produk</span>
                    <span class="font-medium">{{ $produk->nama }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Harga</span>
                    <span id="harga-text" class="font-medium">Rp{{ number_format($varian->harga ?? $produk->harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Stok</span>
                    <span id="stok-text" class="font-medium">{{ $varian->stok ?? $produk->stok }}</span>
                </div>
                <div class="pt-4">
                    <p class="text-gray-500 mb-1">Deskripsi</p>
                    <p id="deskripsi" class="text-gray-800 text-sm leading-relaxed line-clamp-5">
                        {{ $produk->deskripsi }}
                    </p>
                    @if (strlen($produk->deskripsi) > 300)
                        <button id="toggle-deskripsi" type="button" class="text-indigo-600 text-sm mt-1 hover:underline">
                            Baca Selengkapnya
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Varian --}}
        <div>
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="palette" class="w-5 h-5 text-indigo-600"></i>
                <h3 class="text-lg font-semibold text-gray-900">Pilih Varian</h3>
            </div>
            <div class="flex flex-wrap gap-4">
                @foreach ($produk->varian as $v)
                    <button type="button"
                            class="varian-option group border rounded-xl p-3 text-sm text-center bg-white shadow-sm hover:border-indigo-500 hover:shadow-md transition w-28"
                            data-id="{{ $v->id }}"
                            data-harga="{{ $v->harga }}"
                            data-stok="{{ $v->stok }}"
                            data-gambar="{{ asset('storage/' . $v->gambar) }}">
                        <img src="{{ asset('storage/' . $v->gambar) }}" alt="{{ $v->nama }}"
                             class="w-16 h-16 object-cover mx-auto rounded-md mb-2 group-hover:scale-105 transition-transform" />
                        <div class="text-gray-700 group-hover:text-indigo-600">
                            <strong>{{ $v->nama }}</strong><br>
                            Rp{{ number_format($v->harga, 0, ',', '.') }}<br>
                            Stok: {{ $v->stok }}
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </div>

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

    {{-- SECTION: Form Pembelian --}}
    <form id="checkout-form" action="{{ route('user.checkout.start') }}" method="POST">
        @csrf
        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
        <input type="hidden" name="varian_id" id="varian_id">

        <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 space-y-4">
            <div class="flex items-center gap-3 mb-4">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-indigo-500"></i>
                <h3 class="text-xl font-semibold text-gray-900">Pembelian</h3>
            </div>

            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" min="1" value="1" required
                       class="w-full rounded-xl border border-gray-300 px-4 py-3 text-gray-900 shadow-sm focus:ring-4 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            <div class="pt-4">
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition duration-300 shadow hover:shadow-lg">
                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    Lanjut ke Checkout
                </button>
            </div>
        </div>
    </form>

    {{-- SECTION: Penilaian Pembeli --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 space-y-4">
        <div class="flex items-center gap-3 mb-4">
            <i data-lucide="star" class="w-5 h-5 text-yellow-500"></i>
            <h3 class="text-xl font-semibold text-gray-900">Penilaian Pembeli</h3>
        </div>

        @forelse ($produk->penilaian as $penilaian)
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-1">
                    <span class="font-medium text-gray-800">{{ $penilaian->user->name }}</span>
                    <span class="text-yellow-500 font-semibold">⭐ {{ $penilaian->rating }} / 5</span>
                </div>
                <p class="text-gray-700">{{ $penilaian->ulasan }}</p>

                @if ($penilaian->user_id === auth()->id())
                    <form action="{{ route('user.penilaian.destroy', $penilaian->id) }}" method="POST" class="mt-2">
                @csrf
                @method('DELETE')
                        <div class="pt-4">
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition duration-300 shadow hover:shadow-lg">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                Hapus Penilaian
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500">Belum ada penilaian untuk produk ini.</p>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pastikan lucide tidak error
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const varianButtons = document.querySelectorAll('.varian-option');
        const inputVarianId = document.getElementById('varian_id');
        const hargaText = document.getElementById('harga-text');
        const stokText = document.getElementById('stok-text');
        const mainImage = document.getElementById('main-image');
        const checkoutForm = document.getElementById('checkout-form');
        const loader = document.getElementById('loader');

        // Validasi sebelum submit checkout
        if (checkoutForm) {
            const submitButton = checkoutForm.querySelector('button[type="submit"]');

            if (submitButton) {
                submitButton.addEventListener('click', function (e) {
                    const varianTerpilih = inputVarianId.value && inputVarianId.value.trim() !== '';

                    if (!varianTerpilih) {
                        e.preventDefault(); // Cegah submit
                        alert('Silakan pilih varian terlebih dahulu.');

                        // Sembunyikan loader jika muncul
                        if (loader) loader.style.display = 'none';
                        return;
                    }

                    // Tampilkan loader jika valid
                    if (loader) loader.style.display = 'flex';
                });
            }
        }

        // Saat varian dipilih
        varianButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const harga = this.dataset.harga;
                const stok = this.dataset.stok;
                const gambar = this.dataset.gambar;

                inputVarianId.value = id;

                hargaText.textContent = 'Rp' + Number(harga).toLocaleString('id-ID');
                stokText.textContent = stok;
                mainImage.src = gambar;

                // Style
                varianButtons.forEach(btn => btn.classList.remove('ring', 'ring-indigo-400'));
                this.classList.add('ring', 'ring-indigo-400');
            });
        });

        // Toggle deskripsi
        const deskripsi = document.getElementById('deskripsi');
        const toggleBtn = document.getElementById('toggle-deskripsi');

        if (toggleBtn && deskripsi) {
            toggleBtn.addEventListener('click', function () {
                const collapsed = deskripsi.classList.contains('line-clamp-5');
                deskripsi.classList.toggle('line-clamp-5');
                toggleBtn.textContent = collapsed ? 'Sembunyikan' : 'Baca Selengkapnya';
            });
        }
    });
</script>
@endsection
