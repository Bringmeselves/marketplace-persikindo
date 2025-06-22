@extends('layouts.app')

@section('title', 'Edit Toko')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">Edit Toko</h2>

    {{-- Notifikasi --}}
    @if(session('success') || session('error'))
        <div class="space-y-2">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-red-500 bg-red-50 rounded shadow-sm">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-600"></i>
                    <span class="text-sm text-red-800 font-medium">{{ session('error') }}</span>
                </div>
            @endif
        </div>
    @endif

    {{-- Form Edit --}}
    <form action="{{ route('user.toko.update', $toko->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Grid dua kolom --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama Toko --}}
            <div>
                <label for="nama_toko" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}"
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200" required>
                @error('nama_toko')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nomor WA --}}
            <div>
                <label for="nomer_wa" class="block text-sm font-medium text-gray-700">Nomor WA</label>
                <input type="text" name="nomer_wa" id="nomer_wa" value="{{ old('nomer_wa', $toko->nomer_wa) }}"
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200" required>
                @error('nomer_wa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kota --}}
            <div>
                <label for="cities" class="block text-sm font-medium text-gray-700">Kota</label>
                <select name="cities" id="cities"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200" required>
                    <option value="" disabled selected>Pilih Kota</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city['id'] }}" {{ old('cities', $toko->cities) == $city['id'] ? 'selected' : '' }}>
                            {{ $city['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('cities')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label for="foto_toko" class="block text-sm font-medium text-gray-700 mb-2">Foto Toko</label>
                <div id="preview-container" class="w-full h-48 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center cursor-pointer border border-dashed hover:border-gray-400 transition">
                    @if ($toko->foto_toko)
                        <img id="preview-toko" src="{{ asset('storage/' . $toko->foto_toko) }}" alt="Foto Toko" class="w-full h-full object-cover" />
                        <span id="placeholder-toko" class="hidden text-gray-400 text-sm">Klik untuk pilih gambar</span>
                    @else
                        <img id="preview-toko" src="#" alt="Preview Foto Toko" class="hidden w-full h-full object-cover" />
                        <span id="placeholder-toko" class="text-gray-400 text-sm">Klik untuk pilih gambar</span>
                    @endif
                </div>
                <input type="file" name="foto_toko" id="foto_toko" accept="image/*" class="hidden" onchange="previewTokoImage(event)">
                @error('foto_toko')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Keterangan --}}
        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3"
                      class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200"
                      required>{{ old('keterangan', $toko->keterangan) }}</textarea>
            @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Alamat --}}
        <div>
            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3"
                      class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200"
                      required>{{ old('alamat', $toko->alamat) }}</textarea>
            @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- SECTION: Aksi --}}
        <div class="flex justify-center gap-4 pt-6">
            {{-- Tombol Batal --}}
            <button type="button"
                    onclick="window.location='{{ route('user.toko.kelola', $toko->id) }}'"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-gray-100 hover:bg-gray-100 text-gray-700 text-sm font-medium transition-shadow shadow-sm">
                <i data-lucide="x" class="w-5 h-5"></i>
                Batal
            </button>

            {{-- Tombol Simpan --}}
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition-shadow shadow-sm">
                <i data-lucide="save" class="w-5 h-5"></i>
                Simpan
            </button>
        </div>
    </form>
</div>

{{-- Script Lucide dan Preview Gambar --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    function previewTokoImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview-toko');
        const placeholder = document.getElementById('placeholder-toko');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById('preview-container').addEventListener('click', () => {
        document.getElementById('foto_toko').click();
    });
</script>
@endsection
