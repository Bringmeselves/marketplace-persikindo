@extends('layouts.app')

@section('title', 'Pembelian Produk')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">

    {{-- === PRODUK SECTION (GAYA PROFIL TOKO) === --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-6">
        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="box" class="w-6 h-6 text-indigo-500"></i>
                {{ $produk->nama }}
            </h2>
            <p class="text-sm text-gray-500">Informasi lengkap mengenai produk ini.</p>
        </div>

        {{-- Isi Produk --}}
        <div class="flex flex-col md:flex-row gap-6">
            {{-- Gambar --}}
            <div class="flex justify-center items-center w-full md:w-1/3">
                <img id="main-image"
                    src="{{ asset('storage/' . (isset($varian) && $varian->gambar ? $varian->gambar : $produk->gambar)) }}"
                    alt="{{ $produk->nama }}"
                    class="w-72 h-72 object-cover rounded-xl shadow-sm border border-gray-200">
            </div>

            {{-- Info Produk --}}
            <div class="flex-1 space-y-4 text-sm text-gray-700">
                <div class="grid grid-cols-2 gap-y-2">
                    <span class="text-gray-500">Nama Produk</span>
                    <span class="font-medium text-gray-800">{{ $produk->nama }}</span>

                    <span class="text-gray-500">Harga</span>
                    <span id="harga-text" class="font-medium text-gray-800">Rp{{ number_format($varian->harga ?? $produk->harga, 0, ',', '.') }}</span>

                    <span class="text-gray-500">Stok</span>
                    <span id="stok-text" class="font-medium text-gray-800">{{ $varian->stok ?? $produk->stok }}</span>
                </div>

                {{-- Deskripsi --}}
                <div class="pt-4">
                    <p class="text-gray-500 mb-1 font-medium">Deskripsi</p>
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
        @if($produk->varian->count())
        <div>
            <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i data-lucide="palette" class="w-5 h-5 text-indigo-600"></i>
                Pilih Varian
            </h3>
            <div class="flex flex-wrap gap-4 mt-4">
                @foreach ($produk->varian as $v)
                    <button type="button"
                        class="varian-option group border border-gray-200 rounded-xl p-3 bg-white text-center w-28 hover:border-indigo-500 hover:shadow transition"
                        data-id="{{ $v->id }}"
                        data-harga="{{ $v->harga }}"
                        data-stok="{{ $v->stok }}"
                        data-gambar="{{ asset('storage/' . $v->gambar) }}">
                        <img src="{{ asset('storage/' . $v->gambar) }}" alt="{{ $v->nama }}"
                            class="w-16 h-16 object-cover mx-auto rounded-md mb-2 group-hover:scale-105 transition-transform" />
                        <div class="text-gray-700 group-hover:text-indigo-600 text-sm">
                            <strong>{{ $v->nama }}</strong><br>
                            Rp{{ number_format($v->harga, 0, ',', '.') }}<br>
                            <span class="text-xs">Stok: {{ $v->stok }}</span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
        @endif
    </div>

   {{-- === TOKO SECTION === --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-6">

        {{-- Header --}}
        <div class="flex items-center gap-2 border-b pb-4">
            <i data-lucide="store" class="w-6 h-6 text-indigo-500"></i>
            <h2 class="text-2xl font-bold text-gray-900">Informasi Toko</h2>
        </div>

        {{-- Konten --}}
        <a href="{{ route('user.toko.show', $produk->toko->id) }}"
        class="flex items-center gap-6 p-4 border border-gray-100 rounded-xl hover:shadow-md transition">

            {{-- Foto Toko --}}
            @if ($produk->toko->foto_toko)
                <img src="{{ asset('storage/' . $produk->toko->foto_toko) }}"
                    alt="Foto Toko"
                    class="w-20 h-20 object-cover rounded-full border shadow-sm">
            @else
                <div class="w-20 h-20 flex items-center justify-center bg-gray-100 text-gray-500 rounded-full text-center text-xs">
                    Tidak ada<br>foto toko
                </div>
            @endif

            {{-- Info Toko --}}
            <div>
                <h4 class="text-lg font-semibold text-gray-900">
                    {{ $produk->toko->nama_toko }}
                </h4>
                <p class="text-sm text-gray-600">
                    {{ $produk->toko->alamat }}
                </p>
            </div>
        </a>
    </div>

    {{-- === FORM PEMBELIAN === --}}
    <form id="checkout-form" action="{{ route('user.checkout.start') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="produk_id" value="{{ $produk->id }}">
        <input type="hidden" name="varian_id" id="varian_id">

        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-6">

            {{-- Header --}}
            <div class="flex items-center gap-2 border-b pb-4">
                <i data-lucide="shopping-bag" class="w-6 h-6 text-indigo-500"></i>
                <h2 class="text-2xl font-bold text-gray-900">Pembelian</h2>
            </div>

            {{-- Input Jumlah --}}
            <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah" id="jumlah" min="1" value="1" required
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:ring-4 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>

            {{-- Tombol Checkout --}}
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-green-600 text-white py-3 rounded-xl font-semibold hover:bg-green-700 transition duration-300 shadow hover:shadow-lg">
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                Lanjut ke Checkout
            </button>
        </div>
    </form>

    {{-- === PENILAIAN SECTION === --}}
    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-6">
        {{-- Header --}}
        <div class="flex items-center gap-2 border-b pb-4">
            <i data-lucide="star" class="w-6 h-6 text-yellow-500"></i>
            <h2 class="text-2xl font-bold text-gray-900">Penilaian Pembeli</h2>
        </div>

        @forelse ($produk->penilaian as $penilaian)
            <div class="border-t pt-4 space-y-3">
                {{-- Info User dan Rating --}}
                <div class="flex justify-between items-center">
                    <span class="font-medium text-gray-800">{{ $penilaian->user->name }}</span>
                    <span class="text-yellow-500 font-semibold text-sm">⭐ {{ $penilaian->rating }} / 5</span>
                </div>

                {{-- Ulasan --}}
                <p class="text-sm text-gray-700">{{ $penilaian->ulasan }}</p>

                {{-- Tombol Hapus Penilaian (jika user yang memberikan) --}}
                @if ($penilaian->user_id === auth()->id())
                    <form action="{{ route('user.penilaian.destroy', $penilaian->id) }}" method="POST" class="flex justify-end">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition duration-300 shadow hover:shadow-lg">
                            <i data-lucide="trash" class="w-5 h-5"></i>
                            Hapus Penilaian
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-gray-500 text-sm">Belum ada penilaian untuk produk ini.</p>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') lucide.createIcons();

        const varianButtons = document.querySelectorAll('.varian-option');
        const inputVarianId = document.getElementById('varian_id');
        const hargaText = document.getElementById('harga-text');
        const stokText = document.getElementById('stok-text');
        const mainImage = document.getElementById('main-image');
        const checkoutForm = document.getElementById('checkout-form');
        const loader = document.getElementById('loader');

        // Validasi varian saat submit
        if (checkoutForm) {
            const submitButton = checkoutForm.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.addEventListener('click', function (e) {
                    const varianTerpilih = inputVarianId.value && inputVarianId.value.trim() !== '';
                    if (!varianTerpilih) {
                        e.preventDefault();
                        alert('Silakan pilih varian terlebih dahulu.');
                        if (loader) loader.style.display = 'none';
                        return;
                    }
                    if (loader) loader.style.display = 'flex';
                });
            }
        }

        // Saat varian dipilih
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