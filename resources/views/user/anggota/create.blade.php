@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Anggota')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-xl mt-10">
    <h1 class="text-2xl font-bold mb-6">Formulir Pendaftaran Anggota</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form id="form-pendaftaran-anggota" action="{{ route('user.anggota.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Nama Lengkap -->
        <div>
            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            @error('nama_lengkap')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- NIK -->
        <div>
            <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            @error('nik')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Bukti Pendaftaran (KTP) -->
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

        <!-- Nama Perusahaan -->
        <div>
            <label for="nama_perusahaan" class="block text-sm font-medium text-gray-700">Nama Perusahaan</label>
            <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            @error('nama_perusahaan')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Legalitas (Select CV atau PT) -->
        <div>
            <label for="legalitas" class="block text-sm font-medium text-gray-700">Legalitas</label>
            <select name="legalitas" id="legalitas" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                <option value="" disabled {{ old('legalitas') ? '' : 'selected' }}>Pilih Legalitas</option>
                <option value="CV" {{ old('legalitas') == 'CV' ? 'selected' : '' }}>CV</option>
                <option value="PT" {{ old('legalitas') == 'PT' ? 'selected' : '' }}>PT</option>
            </select>
            @error('legalitas')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- NIB -->
        <div>
            <label for="nib" class="block text-sm font-medium text-gray-700">NIB</label>
            <input type="text" name="nib" id="nib" value="{{ old('nib') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            @error('nib')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- NPWP -->
        <div>
            <label for="npwp" class="block text-sm font-medium text-gray-700">NPWP</label>
            <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
            @error('npwp')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sertifikat Halal (File) -->
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

        <!-- PIRT (File) -->
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

        <!-- Tombol Submit -->
        <div class="flex justify-end mt-8">
            <button type="submit"
                    class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition">
                Kirim Pendaftaran
            </button>
        </div>
    </form>
</div>
@endsection
