@extends('layouts.app')

@section('title', 'Buat Toko')

@section('content')
<div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-xl mt-10">
    <h1 class="text-2xl font-bold mb-6">Buat Toko Baru</h1>

    {{-- Notifikasi sukses / error --}}
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @elseif(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Buat Toko --}}
    <form id="form-buat-toko" action="{{ route('user.toko.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Gunakan grid 2 kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nama Toko --}}
            <div>
                <label for="nama_toko" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                       required>
                @error('nama_toko')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor WA --}}
            <div>
                <label for="nomer_wa" class="block text-sm font-medium text-gray-700">Nomor WA</label>
                <input type="text" name="nomer_wa" id="nomer_wa" value="{{ old('nomer_wa') }}"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                       required>
                @error('nomer_wa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kota --}}
            <div>
                <label for="cities" class="block text-sm font-medium text-gray-700">Kota</label>
                <select name="cities" id="cities"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                        required>
                    <option value="" disabled selected>Pilih Kota</option>
                    @foreach($cities as $city)
                        <option value="{{ $city['id'] }}" {{ old('cities') == $city['id'] ? 'selected' : '' }}>
                            {{ $city['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('cities')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Foto Toko dengan preview interaktif --}}
            <div class="flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 transition hover:border-gray-500 cursor-pointer">
                <label for="foto_toko" class="mb-4 text-lg font-semibold text-gray-700 cursor-pointer">Tambah Foto Toko</label>
                <input type="file" name="foto_toko" id="foto_toko" accept="image/*" class="hidden" onchange="previewTokoImage(event)">
                
                {{-- Area preview --}}
                <div id="preview-container" class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden relative">
                    <img id="preview-toko" src="#" alt="Preview Foto Toko" class="hidden object-cover w-full h-full rounded-lg" />
                    <span id="placeholder-toko" class="text-gray-400 select-none">Klik untuk pilih gambar</span>
                </div>

                @error('foto_toko')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Keterangan toko --}}
        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                      required>{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat toko --}}
        <div>
            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200"
                      required>{{ old('alamat') }}</textarea>
            @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div>
            <button type="submit"
                class="w-full bg-indigo-600 text-white py-4 rounded-2xl font-semibold text-xl hover:bg-indigo-700 transition-shadow shadow-md hover:shadow-lg">
                Simpan Toko
            </button>
        </div>
    </form>
</div>

{{-- Menampilkan toko yang sudah dibuat --}}
<div class="max-w-7xl mx-auto mt-10">
    <h2 class="text-xl font-bold mb-6">Toko Anda</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @if(isset($toko) && $toko)
            {{-- Kartu toko --}}
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <img src="{{ $toko->foto_toko ? asset('storage/' . $toko->foto_toko) : asset('images/default-toko.png') }}" 
                     alt="Foto Toko" class="w-full h-40 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-bold">{{ $toko->nama_toko }}</h3>
                    <p class="text-sm text-gray-600">{{ $toko->alamat }}</p>
                    <p class="text-sm text-gray-600">Kota: {{ $toko->city_name ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Provinsi: {{ $toko->province_name ?? '-' }}</p>
                    <p class="text-sm text-gray-600">WA: {{ $toko->nomer_wa }}</p>
                    <div class="mt-4 flex justify-between items-center">
                        <a href="{{ route('user.toko.edit', $toko->id) }}" 
                           class="text-indigo-600 hover:underline">Edit</a>
                        <form action="{{ route('user.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus toko ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <p class="text-gray-600">Anda belum memiliki toko.</p>
        @endif
    </div>
</div>

{{-- Script untuk preview gambar --}}
<script>
    function previewTokoImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview-toko');
        const placeholder = document.getElementById('placeholder-toko');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden'); // Tampilkan gambar
                placeholder.classList.add('hidden'); // Sembunyikan teks placeholder
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    // Klik area preview membuka file picker
    document.getElementById('preview-container').addEventListener('click', () => {
        document.getElementById('foto_toko').click();
    });
</script>
@endsection
