@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Edit Produk</h2>
            <p class="text-sm text-gray-500">Perbarui informasi produk Anda di bawah ini.</p>
        </div>

         {{-- Alert Error --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
                <div>
                    <strong class="block font-semibold">Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 mt-0.5"></i>
                <div>
                    <strong class="block font-semibold">{{ session('success') }}</strong>
                </div>
            </div>
        @endif

        <form action="{{ route('user.produk.update', $produk->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Kolom Kiri: Preview Gambar --}}
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">Gambar Utama Produk</label>
                    <div 
                        id="preview-container" 
                        class="relative w-full h-64 bg-gray-100 border border-dashed border-gray-300 hover:border-gray-500 rounded-xl flex items-center justify-center cursor-pointer overflow-hidden"
                    >
                        <img id="preview-produk" src="{{ asset('storage/' . $produk->gambar) }}" alt="Preview" class="absolute inset-0 w-full h-full object-cover rounded-xl" />
                        <span id="placeholder-produk" class="text-gray-400 text-sm z-10 hidden">Klik untuk pilih gambar</span>
                    </div>
                    <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden">
                    @error('gambar') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                {{-- Kolom Kanan: Form --}}
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $produk->nama) }}"
                            class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900" required>
                    </div>

                    <div>
                        <label for="kategori_id" class="block text-sm font-semibold text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" id="kategori_id"
                            class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900" required>
                            <option disabled>Pilih Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ $kat->id == $produk->kategori_id ? 'selected' : '' }}>{{ $kat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="harga" class="block text-sm font-semibold text-gray-700 mb-1">Harga</label>
                            <input type="number" name="harga" id="harga" value="{{ old('harga', $produk->harga) }}"
                                class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900" required>
                        </div>
                        <div>
                            <label for="stok" class="block text-sm font-semibold text-gray-700 mb-1">Stok</label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok', $produk->stok) }}"
                                class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900" required>
                        </div>
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="4"
                            class="w-full rounded-xl border border-gray-300 px-5 py-3 resize-none shadow-sm focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900" required>{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Varian Produk --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-900">Varian Produk</h3>
                <div id="varian-container" class="space-y-4">
                    @foreach($produk->varian as $var)
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                        <input type="text" name="varian[nama][]" value="{{ $var->nama }}" placeholder="Nama Varian" class="rounded-xl border-gray-300 px-4 py-2" required>
                        <input type="number" name="varian[stok][]" value="{{ $var->stok }}" placeholder="Stok" class="rounded-xl border-gray-300 px-4 py-2" required>
                        <input type="number" name="varian[harga][]" value="{{ $var->harga }}" placeholder="Harga" class="rounded-xl border-gray-300 px-4 py-2" required>
                        <input type="file" name="varian[gambar][]" class="rounded-xl border-gray-300 px-4 py-2">
                        <button type="button" onclick="hapusVarian(this)" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                            <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                        </button>
                    </div>
                    @endforeach
                </div>

                <button type="button" onclick="tambahVarian()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Varian
                </button>
            </div>

            <div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                    <i data-lucide="save" class="w-5 h-5"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script --}}
<script>
    document.getElementById('preview-container').addEventListener('click', () => {
        document.getElementById('gambar').click();
    });

    document.getElementById('gambar').addEventListener('change', function (event) {
        const input = event.target;
        const preview = document.getElementById('preview-produk');
        const placeholder = document.getElementById('placeholder-produk');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    function tambahVarian() {
        const container = document.getElementById('varian-container');
        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-1', 'md:grid-cols-5', 'gap-4', 'items-center');
        div.innerHTML = `
            <input type="text" name="varian[nama][]" placeholder="Nama Varian" class="rounded-xl border-gray-300 px-4 py-2" required>
            <input type="number" name="varian[stok][]" placeholder="Stok" class="rounded-xl border-gray-300 px-4 py-2" required>
            <input type="number" name="varian[harga][]" placeholder="Harga" class="rounded-xl border-gray-300 px-4 py-2" required>
            <input type="file" name="varian[gambar][]" accept="image/*" class="rounded-xl border-gray-300 px-4 py-2">
            <button type="button" onclick="hapusVarian(this)" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
            </button>
        `;
        container.appendChild(div);
        lucide.createIcons();
    }

    function hapusVarian(button) {
        button.parentElement.remove();
    }
</script>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
