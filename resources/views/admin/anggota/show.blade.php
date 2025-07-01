@extends('layouts.admin')

@section('title', 'Detail Anggota')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">
    {{-- Judul Halaman --}}
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b flex items-center gap-2">
        <i data-lucide="user-check" class="w-7 h-7 text-indigo-600"></i>
        Detail Anggota
    </h2>

    {{-- Informasi Anggota --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- Nama Lengkap --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="user" class="w-4 h-4 text-gray-500"></i> Nama Lengkap
            </h3>
            <p class="text-lg text-gray-900 font-semibold">{{ $anggota->nama_lengkap }}</p>
        </div>

        {{-- NIK --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="id-card" class="w-4 h-4 text-gray-500"></i> NIK
            </h3>
            <p class="text-lg text-gray-900 font-semibold">{{ $anggota->nik }}</p>
        </div>

        {{-- Nama Perusahaan --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="building" class="w-4 h-4 text-gray-500"></i> Nama Perusahaan
            </h3>
            <p class="text-lg text-gray-900 font-semibold">{{ $anggota->nama_perusahaan }}</p>
        </div>

        {{-- Legalitas --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="badge-check" class="w-4 h-4 text-gray-500"></i> Legalitas
            </h3>
            <p class="text-lg text-gray-900 font-semibold">{{ $anggota->legalitas }}</p>
        </div>

        {{-- NIB --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="file-text" class="w-4 h-4 text-gray-500"></i> NIB
            </h3>
            <p class="text-lg text-gray-900 font-semibold">{{ $anggota->nib }}</p>
        </div>

        {{-- NPWP --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="file-digit" class="w-4 h-4 text-gray-500"></i> NPWP
            </h3>
            <p class="text-lg text-gray-900 font-semibold">{{ $anggota->npwp }}</p>
        </div>

        {{-- Sertifikat Halal --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="check-circle" class="w-4 h-4 text-gray-500"></i> Sertifikat Halal
            </h3>
            @if($anggota->sertifikat_halal)
                <a href="{{ Storage::url($anggota->sertifikat_halal) }}" target="_blank"
                   class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                    <i data-lucide="eye" class="w-4 h-4"></i> Lihat Sertifikat Halal
                </a>
            @else
                <p class="text-red-600 text-sm">Tidak ada sertifikat halal.</p>
            @endif
        </div>

        {{-- PIRT --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
            <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
                <i data-lucide="file-badge" class="w-4 h-4 text-gray-500"></i> PIRT
            </h3>
            @if($anggota->pirt)
                <a href="{{ Storage::url($anggota->pirt) }}" target="_blank"
                   class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                    <i data-lucide="eye" class="w-4 h-4"></i> Lihat PIRT
                </a>
            @else
                <p class="text-red-600 text-sm">Tidak ada PIRT.</p>
            @endif
        </div>
    </div>

    {{-- Bukti Pendaftaran --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-2">
        <h3 class="text-sm text-gray-500 font-medium flex items-center gap-1">
            <i data-lucide="image" class="w-4 h-4 text-gray-500"></i> Bukti Pendaftaran (KTP)
        </h3>
        @if($anggota->bukti_pendaftaran)
            <a href="{{ Storage::url($anggota->bukti_pendaftaran) }}" target="_blank"
               class="text-indigo-600 hover:text-indigo-800 font-medium text-sm flex items-center gap-1">
                <i data-lucide="eye" class="w-4 h-4"></i> Lihat Bukti Pendaftaran
            </a>
        @else
            <p class="text-red-600 text-sm">Tidak ada bukti pendaftaran.</p>
        @endif
    </div>
</div>

{{-- Inisialisasi ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
