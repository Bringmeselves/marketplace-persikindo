@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Anggota')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    {{-- Judul --}}
    <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
        <i data-lucide="form-input" class="w-7 h-7 text-indigo-600"></i>
        Formulir Pendaftaran Anggota
    </h2>

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
    <form id="form-pendaftaran-anggota" action="{{ route('user.anggota.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Nama Lengkap --}}
            <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200">
                @error('nama_lengkap')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIK --}}
            <div>
                <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200">
                @error('nik')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama Perusahaan --}}
            <div>
                <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
                <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200">
                @error('nama_perusahaan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Legalitas --}}
            <div>
                <label for="legalitas" class="block text-sm font-medium text-gray-700">Legalitas</label>
                <select name="legalitas" id="legalitas" required
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200">
                    <option value="" disabled {{ old('legalitas') ? '' : 'selected' }}>Pilih Legalitas</option>
                    <option value="CV" {{ old('legalitas') == 'CV' ? 'selected' : '' }}>CV</option>
                    <option value="PT" {{ old('legalitas') == 'PT' ? 'selected' : '' }}>PT</option>
                </select>
                @error('legalitas')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NIB --}}
            <div>
                <label for="nib" class="block text-sm font-medium text-gray-700">NIB</label>
                <input type="text" name="nib" id="nib" value="{{ old('nib') }}" required
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200">
                @error('nib')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- NPWP --}}
            <div>
                <label for="npwp" class="block text-sm font-medium text-gray-700">NPWP</label>
                <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}" required
                       class="mt-1 block w-full rounded-xl border-gray-300 focus:ring-indigo-200">
                @error('npwp')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Bukti Pendaftaran (KTP) --}}
        <div>
            <label for="bukti_pendaftaran" class="block text-sm font-medium text-gray-700">Bukti Pendaftaran (KTP)</label>
            <input type="file" name="bukti_pendaftaran" id="bukti_pendaftaran" required
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                          file:rounded-md file:border-0 file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('bukti_pendaftaran')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Dokumen Opsional --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Sertifikat Halal --}}
            <div>
                <label for="sertifikat_halal" class="block text-sm font-medium text-gray-700">Sertifikat Halal</label>
                <input type="file" name="sertifikat_halal" id="sertifikat_halal"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0 file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('sertifikat_halal')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PIRT --}}
            <div>
                <label for="pirt" class="block text-sm font-medium text-gray-700">PIRT</label>
                <input type="file" name="pirt" id="pirt"
                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-md file:border-0 file:text-sm file:font-semibold
                              file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('pirt')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Tombol Submit --}}
        <div class="pt-4">
            <button type="submit"
                    class="inline-flex items-center justify-center w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold transition">
                <i data-lucide="send" class="w-5 h-5 mr-2"></i>
                Kirim Pendaftaran
            </button>
        </div>
    </form>
</div>

{{-- Script --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
