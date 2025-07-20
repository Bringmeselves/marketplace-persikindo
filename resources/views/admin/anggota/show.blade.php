@extends('layouts.admin')

@section('title', 'Detail Anggota')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                <i data-lucide="user-check" class="w-6 h-6 text-indigo-600"></i>
                Detail Anggota
            </h2>
            <p class="text-sm text-gray-500">Informasi lengkap mengenai anggota terdaftar di Persikindo.</p>
        </div>

        {{-- Data Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm text-gray-700">
            @php
                $dataList = [
                    ['icon' => 'user', 'label' => 'Nama Lengkap', 'value' => $anggota->nama_lengkap],
                    ['icon' => 'id-card', 'label' => 'NIK', 'value' => $anggota->nik],
                    ['icon' => 'building', 'label' => 'Nama Perusahaan', 'value' => $anggota->nama_perusahaan],
                    ['icon' => 'badge-check', 'label' => 'Legalitas', 'value' => $anggota->legalitas],
                    ['icon' => 'file-text', 'label' => 'NIB', 'value' => $anggota->nib],
                    ['icon' => 'file-digit', 'label' => 'NPWP', 'value' => $anggota->npwp],
                ];
            @endphp

            @foreach ($dataList as $item)
                <div class="p-4 border border-gray-200 rounded-xl space-y-1 bg-gray-50">
                    <div class="flex items-center gap-2 text-gray-500 font-medium">
                        <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
                        {{ $item['label'] }}
                    </div>
                    <div class="text-base font-semibold text-gray-900">{{ $item['value'] }}</div>
                </div>
            @endforeach

            {{-- Sertifikat Halal --}}
            <div class="p-4 border border-gray-200 rounded-xl space-y-1 bg-gray-50">
                <div class="flex items-center gap-2 text-gray-500 font-medium">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Sertifikat Halal
                </div>
                @if($anggota->sertifikat_halal)
                    <a href="{{ Storage::url($anggota->sertifikat_halal) }}" target="_blank"
                       class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                        <i data-lucide="eye" class="w-4 h-4"></i> Lihat Sertifikat Halal
                    </a>
                @else
                    <p class="text-red-600 text-sm">Tidak ada sertifikat halal.</p>
                @endif
            </div>

            {{-- PIRT --}}
            <div class="p-4 border border-gray-200 rounded-xl space-y-1 bg-gray-50">
                <div class="flex items-center gap-2 text-gray-500 font-medium">
                    <i data-lucide="file-badge" class="w-4 h-4"></i>
                    PIRT
                </div>
                @if($anggota->pirt)
                    <a href="{{ Storage::url($anggota->pirt) }}" target="_blank"
                       class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                        <i data-lucide="eye" class="w-4 h-4"></i> Lihat PIRT
                    </a>
                @else
                    <p class="text-red-600 text-sm">Tidak ada PIRT.</p>
                @endif
            </div>

             {{-- Bukti Pendaftaran --}}
            <div class="border-t pt-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-500 font-medium">
                        <i data-lucide="image" class="w-4 h-4"></i>
                        Bukti Pendaftaran (KTP)
                    </div>
                    @if($anggota->bukti_pendaftaran)
                        <a href="{{ Storage::url($anggota->bukti_pendaftaran) }}" target="_blank"
                        class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                            <i data-lucide="eye" class="w-4 h-4"></i> Lihat Bukti Pendaftaran
                        </a>
                    @else
                        <p class="text-red-600 text-sm">Tidak ada bukti pendaftaran.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Inisialisasi ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
