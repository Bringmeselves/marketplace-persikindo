@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4 text-2xl font-bold">Detail Anggota</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Nama Lengkap -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">Nama Lengkap</h3>
                <p class="mt-2 text-gray-600">{{ $anggota->nama_lengkap }}</p>
            </div>

            <!-- NIK -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">NIK</h3>
                <p class="mt-2 text-gray-600">{{ $anggota->nik }}</p>
            </div>

            <!-- Nama Perusahaan -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">Nama Perusahaan</h3>
                <p class="mt-2 text-gray-600">{{ $anggota->nama_perusahaan }}</p>
            </div>

            <!-- Legalitas -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">Legalitas</h3>
                <p class="mt-2 text-gray-600">{{ $anggota->legalitas }}</p>
            </div>

            <!-- NIB -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">NIB</h3>
                <p class="mt-2 text-gray-600">{{ $anggota->nib }}</p>
            </div>

            <!-- NPWP -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">NPWP</h3>
                <p class="mt-2 text-gray-600">{{ $anggota->npwp }}</p>
            </div>

            <!-- Sertifikat Halal -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">Sertifikat Halal</h3>
                <div class="mt-2">
                    @if($anggota->sertifikat_halal)
                        <a href="{{ Storage::url($anggota->sertifikat_halal) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">Lihat Sertifikat Halal</a>
                    @else
                        <p class="text-red-600">Tidak ada sertifikat halal.</p>
                    @endif
                </div>
            </div>

            <!-- PIRT -->
            <div class="bg-white p-4 border rounded-lg shadow-md">
                <h3 class="font-semibold text-lg text-gray-700">PIRT</h3>
                <div class="mt-2">
                    @if($anggota->pirt)
                        <a href="{{ Storage::url($anggota->pirt) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">Lihat PIRT</a>
                    @else
                        <p class="text-red-600">Tidak ada PIRT.</p>
                    @endif
                </div>
            </div>

            <!-- Bukti Pendaftaran -->
            <div class="bg-white p-4 border rounded-lg shadow-md col-span-2 sm:col-span-1 lg:col-span-2 xl:col-span-4">
                <h3 class="font-semibold text-lg text-gray-700">Bukti Pendaftaran (KTP)</h3>
                <div class="mt-2">
                    @if($anggota->bukti_pendaftaran)
                        <a href="{{ Storage::url($anggota->bukti_pendaftaran) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">Lihat Bukti Pendaftaran</a>
                    @else
                        <p class="text-red-600">Tidak ada bukti pendaftaran.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
