@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Judul Halaman -->
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900">Tambah Produk Baru</h1>

    <!-- Pesan Sukses atau Error -->
    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 border border-green-200 text-green-700 p-4 shadow-sm">
            {{ session('success') }}
        </div>
    @elseif (session('error'))
        <div class="mb-6 rounded-lg bg-red-50 border border-red-200 text-red-700 p-4 shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form Tambah Produk -->
    <form action="{{ route('user.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        @csrf

        <!-- Bagian Upload Gambar Produk -->
        <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 transition hover:border-gray-500 cursor-pointer">
            <label for="gambar" class="mb-4 text-lg font-semibold text-gray-700 cursor-pointer">Tambah Foto Produk</label>
            <input type="file" name="gambar" id="gambar" accept="image/*" 
                class="hidden"
                @error('gambar') aria-invalid="true" @enderror
                onchange="previewImage(event)">
            <div id="preview-container" class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden relative">
                <img id="preview" src="#" alt="Preview Gambar" class="hidden object-cover w-full h-full rounded-lg" />
                <span id="placeholder" class="text-gray-400 select-none">Klik untuk pilih gambar</span>
            </div>
            @error('gambar')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bagian Input Data Produk -->
        <div class="space-y-6">
            <!-- Nama Produk -->
            <div>
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           shadow-sm placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('nama') border-red-500 @enderror">
                @error('nama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi Produk -->
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Produk</label>
                <textarea name="deskripsi" id="deskripsi" rows="4" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           shadow-sm placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga Produk -->
            <div>
                <label for="harga" class="block text-sm font-medium text-gray-700">Harga Produk</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           shadow-sm placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('harga') border-red-500 @enderror" min="0" step="1000">
                @error('harga')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Stok Produk -->
            <div>
                <label for="stok" class="block text-sm font-medium text-gray-700">Stok Produk</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok') }}" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           shadow-sm placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('stok') border-red-500 @enderror" min="0" step="1">
                @error('stok')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Produk -->
            <div>
                <label for="berat" class="block text-sm font-medium text-gray-700">Berat Produk (gram)</label>
                <input type="number" name="berat" id="berat" value="{{ old('berat', 1000) }}" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           shadow-sm placeholder-gray-400
                           focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('berat') border-red-500 @enderror" min="0" step="1">
                @error('berat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori Produk -->
            <div>
                <label for="kategori_id" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                <select name="kategori_id" id="kategori_id" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2
                           shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                           @error('kategori_id') border-red-500 @enderror">
                    <option value="" disabled {{ old('kategori_id') ? '' : 'selected' }}>Pilih Kategori</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}" {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
                @error('kategori_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Submit -->
            <div>
                <button type="submit"
                    class="w-full bg-black text-white py-3 rounded-md font-semibold hover:bg-gray-800 transition">
                    Simpan Produk
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    // Fungsi preview gambar saat upload
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

    // Membuat div preview clickable untuk membuka file selector
    document.getElementById('preview-container').addEventListener('click', () => {
        document.getElementById('gambar').click();
    });
</script>
@endsection
