@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900">Tambah Produk Baru</h1>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 p-4 shadow-sm">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 p-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('user.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @csrf

        <!-- Upload Gambar Produk -->
        <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 transition hover:border-gray-500 cursor-pointer">
            <label for="gambar" class="mb-4 text-lg font-semibold text-gray-700 cursor-pointer">Tambah Foto Produk</label>
            <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden" onchange="previewImage(event)">
            <div id="preview-container" class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden relative">
                <img id="preview" src="#" alt="Preview Gambar" class="hidden object-cover w-full h-full rounded-lg" />
                <span id="placeholder" class="text-gray-400 select-none">Klik untuk pilih gambar</span>
            </div>
            @error('gambar')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Input Data Produk -->
        <div class="space-y-6">
            <!-- Nama Produk -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Deskripsi Produk -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Harga Produk -->
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700">Harga Produk</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="0" step="1000">
                @error('harga')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Stok Produk -->
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700">Stok Produk</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok') }}" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="0" step="1">
                @error('stok')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Berat Produk -->
            <div>
                <label for="berat" class="block text-sm font-medium text-gray-700">Berat Produk (gram)</label>
                <input type="number" name="berat" id="berat" value="{{ old('berat', 1000) }}" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" min="0" step="1">
                @error('berat')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Kategori Produk -->
            <div>
                <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                <select name="kategori_id" id="kategori_id" required class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled {{ old('kategori_id') ? '' : 'selected' }}>Pilih Kategori</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <!-- Input Varian Produk -->
        <div class="lg:col-span-2 mt-8">
            <h2 class="text-lg font-semibold mb-4">Varian Produk</h2>
            <div id="varian-container" class="space-y-6">
                <!-- Placeholder Varian Pertama -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text" name="varian[nama][]" placeholder="Nama Varian (contoh: Ukuran M)" class="border border-gray-300 rounded-md px-3 py-2" required>
                    <input type="number" name="varian[stok][]" placeholder="Stok" class="border border-gray-300 rounded-md px-3 py-2" min="0" required>
                    <input type="number" name="varian[harga][]" placeholder="Harga" class="border border-gray-300 rounded-md px-3 py-2" min="0" step="100" required>
                    <input type="file" name="varian[gambar][]" accept="image/*" class="border border-gray-300 rounded-md px-3 py-2">
                </div>
            </div>
            <button type="button" onclick="addVarian()" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Tambah Varian</button>
        </div>

        <!-- Tombol Submit -->
        <div class="lg:col-span-2">
            <button type="submit" class="w-full bg-black text-white py-3 rounded-md font-semibold hover:bg-gray-800 transition">
                Simpan Produk
            </button>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('placeholder');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    document.getElementById('preview-container').addEventListener('click', () => {
        document.getElementById('gambar').click();
    });

    // Fungsi untuk menambahkan input varian baru
    function addVarian() {
        const container = document.getElementById('varian-container');
        const div = document.createElement('div');
        div.classList.add('grid', 'grid-cols-1', 'md:grid-cols-4', 'gap-4');
        div.innerHTML = `
            <input type="text" name="varian[nama][]" placeholder="Nama Varian" class="border border-gray-300 rounded-md px-3 py-2" required>
            <input type="number" name="varian[stok][]" placeholder="Stok" class="border border-gray-300 rounded-md px-3 py-2" min="0" required>
            <input type="number" name="varian[harga][]" placeholder="Harga" class="border border-gray-300 rounded-md px-3 py-2" min="0" step="100" required>
            <input type="file" name="varian[gambar][]" accept="image/*" class="border border-gray-300 rounded-md px-3 py-2">
        `;
        container.appendChild(div);
    }
</script>
@endsection