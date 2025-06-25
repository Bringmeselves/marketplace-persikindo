@extends('layouts.app')

@section('title', 'Buat Toko')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900">Buat Toko Baru</h2>

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

    {{-- Form --}}
    <form action="{{ route('user.toko.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="nama_toko" class="block text-sm font-medium text-gray-700">Nama Toko</label>
                <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}"
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200"
                       required>
                @error('nama_toko')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="nomer_wa" class="block text-sm font-medium text-gray-700">Nomor WA</label>
                <input type="text" name="nomer_wa" id="nomer_wa" value="{{ old('nomer_wa') }}"
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200"
                       required>
                @error('nomer_wa')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="origin" class="block text-sm font-medium text-gray-700">Kota Asal</label>
                <select name="origin" id="origin"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200"
                        required>
                    <option value="" disabled selected>Pilih Kota</option>
                    @foreach($origins as $origin)
                        <option value="{{ $origin['id'] }}" {{ old('origin') == $origin['id'] ? 'selected' : '' }}>
                            {{ $origin['name'] }}
                        </option>
                    @endforeach
                </select>
                @error('origin')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label for="foto_toko" class="block text-sm font-medium text-gray-700 mb-2">Foto Toko</label>
                <div id="preview-container" class="w-full h-48 rounded-xl overflow-hidden bg-gray-100 flex items-center justify-center cursor-pointer border border-dashed hover:border-gray-400 transition">
                    <img id="preview-toko" src="#" alt="Preview Foto Toko" class="hidden w-full h-full object-cover" />
                    <span id="placeholder-toko" class="text-gray-400 text-sm">Klik untuk pilih gambar</span>
                </div>
                <input type="file" name="foto_toko" id="foto_toko" accept="image/*" class="hidden" onchange="previewTokoImage(event)">
                @error('foto_toko')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
            <textarea name="keterangan" id="keterangan" rows="3"
                      class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200"
                      required>{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea name="alamat" id="alamat" rows="3"
                      class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200"
                      required>{{ old('alamat') }}</textarea>
            @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <button type="submit"
                    class="inline-flex items-center justify-center w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
                <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i>
                Simpan Toko
            </button>
        </div>
    </form>

    {{-- Toko yang sudah ada --}}
    @if(isset($toko))
        <div class="pt-10">
            <h3 class="text-2xl font-bold mb-4">Toko Anda</h3>
            <div class="bg-white rounded-xl shadow p-6 space-y-3">
                <div class="w-full h-40 rounded-lg overflow-hidden bg-gray-100">
                    <img src="{{ $toko->foto_toko ? asset('storage/' . $toko->foto_toko) : asset('images/default-toko.png') }}" 
                         alt="Foto Toko" class="w-full h-full object-cover">
                </div>
                <div class="space-y-1">
                    <p class="font-semibold text-lg">{{ $toko->nama_toko }}</p>
                    <p class="text-sm text-gray-600">{{ $toko->alamat }}</p>
                    <p class="text-sm text-gray-600">Kota: {{ $toko->city_name ?? '-' }}</p>
                    <p class="text-sm text-gray-600">Provinsi: {{ $toko->province_name ?? '-' }}</p>
                    <p class="text-sm text-gray-600">WA: {{ $toko->nomer_wa }}</p>
                </div>
                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('user.toko.edit', $toko->id) }}"
                       class="text-indigo-600 hover:underline text-sm">Edit</a>
                    <form action="{{ route('user.toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus toko ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-sm">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

{{-- Script --}}
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
