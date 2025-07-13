@extends('layouts.app')

@section('title', 'Buat Toko')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Buat Toko Baru</h2>
            <p class="text-sm text-gray-500">Lengkapi data berikut untuk membuat toko Anda.</p>
        </div>

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
                {{-- Nama Toko --}}
                <div>
                    <label for="nama_toko" class="block text-sm font-semibold text-gray-700 mb-2">Nama Toko</label>
                    <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}"
                           class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3"
                           required>
                    @error('nama_toko')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nomor WA --}}
                <div>
                    <label for="nomer_wa" class="block text-sm font-semibold text-gray-700 mb-2">Nomor WA</label>
                    <input type="text" name="nomer_wa" id="nomer_wa" value="{{ old('nomer_wa') }}"
                           class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3"
                           required>
                    @error('nomer_wa')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kota Asal --}}
                <div>
                    <label for="origin" class="block text-sm font-semibold text-gray-700 mb-2">Kota Asal</label>
                    <select name="origin" id="origin"
                            class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3"
                            required>
                        <option value="" disabled {{ old('origin', $toko->origin ?? null) ? '' : 'selected' }}>Pilih Kota</option>
                        @foreach ($origin as $city)
                            <option value="{{ $city['id'] }}" {{ old('origin', $toko->origin ?? null) == $city['id'] ? 'selected' : '' }}>
                                {{ $city['label'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('origin')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Foto Toko --}}
                <div>
                    <label for="foto_toko" class="block text-sm font-semibold text-gray-700 mb-2">Foto Toko</label>
                    <div id="preview-container"
                        class="w-48 h-48 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center cursor-pointer border border-dashed hover:border-gray-400 transition mx-auto">
                        <img id="preview-toko"
                            src="#"
                            alt="Preview Foto Toko"
                            class="hidden w-full h-full object-cover" />
                        <span id="placeholder-toko" class="text-gray-400 text-sm">Klik untuk pilih gambar</span>
                    </div>
                    <input type="file" name="foto_toko" id="foto_toko"
                        accept="image/*"
                        class="hidden"
                        onchange="previewTokoImage(event)">
                    @error('foto_toko')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3"
                          class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 resize-none"
                          required>{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Alamat --}}
            <div>
                <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                <textarea name="alamat" id="alamat" rows="3"
                          class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 resize-none"
                          required>{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-4">
                <button type="submit"
                        class="inline-flex items-center justify-center w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
                    <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i>
                    Simpan Toko
                </button>
            </div>
        </form>
    </div>
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
