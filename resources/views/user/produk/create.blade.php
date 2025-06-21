@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Tambah Produk Baru</h2>

    <form action="{{ route('user.produk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Kolom Kiri: Preview Gambar --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Utama Produk</label>
                
                <div 
                    id="preview-container" 
                    class="relative w-full h-64 bg-gray-100 border border-dashed border-gray-300 hover:border-gray-500 rounded-xl flex items-center justify-center cursor-pointer overflow-hidden"
                >
                    <img id="preview-produk" src="#" alt="Preview" class="hidden absolute inset-0 w-full h-full object-cover rounded-xl" />
                    <span id="placeholder-produk" class="text-gray-400 text-sm z-10">Klik untuk pilih gambar</span>
                </div>
                
                <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden">
                @error('gambar') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
            </div>

            {{-- Kolom Kanan: Form --}}
            <div class="md:col-span-2 space-y-6">
                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="nama" id="nama" class="mt-1 block w-full rounded-xl border-gray-300" required>
                </div>

                <div>
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="mt-1 block w-full rounded-xl border-gray-300" required>
                        <option disabled selected>Pilih Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
                        <input type="number" name="harga" id="harga" class="mt-1 block w-full rounded-xl border-gray-300" required>
                    </div>
                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input type="number" name="stok" id="stok" class="mt-1 block w-full rounded-xl border-gray-300" required>
                    </div>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-xl border-gray-300" required></textarea>
                </div>
            </div>
        </div>

        {{-- Varian Produk --}}
        <div class="mt-10 space-y-4">
            <h3 class="text-lg font-semibold text-gray-900">Varian Produk</h3>
            <div id="varian-container" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                    <input type="text" name="varian[nama][]" placeholder="Nama Varian" class="rounded-xl border-gray-300" required>
                    <input type="number" name="varian[stok][]" placeholder="Stok" class="rounded-xl border-gray-300" required>
                    <input type="number" name="varian[harga][]" placeholder="Harga" class="rounded-xl border-gray-300" required>
                    <input type="file" name="varian[gambar][]" accept="image/*" class="rounded-xl border-gray-300">
                    <button type="button" onclick="hapusVarian(this)" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                    </button>
                </div>
            </div>

            <button type="button" onclick="tambahVarian()" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Varian
            </button>
        </div>

        <div class="mt-8">
            <button type="submit" class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                <i data-lucide="save" class="w-5 h-5"></i> Simpan Produk
            </button>
        </div>
    </form>
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
                preview.classList.remove('hidden');
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
            <input type="text" name="varian[nama][]" placeholder="Nama Varian" class="rounded-xl border-gray-300" required>
            <input type="number" name="varian[stok][]" placeholder="Stok" class="rounded-xl border-gray-300" required>
            <input type="number" name="varian[harga][]" placeholder="Harga" class="rounded-xl border-gray-300" required>
            <input type="file" name="varian[gambar][]" accept="image/*" class="rounded-xl border-gray-300">
            <button type="button" onclick="hapusVarian(this)" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
            </button>
        `;
        container.appendChild(div);
        lucide.createIcons(); // refresh icon setelah tambah
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
