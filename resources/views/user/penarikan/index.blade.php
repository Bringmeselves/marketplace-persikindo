@extends('layouts.app')

@section('title', 'Riwayat Penarikan')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="flex justify-between items-center border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Riwayat Penarikan</h2>
                <p class="text-sm text-gray-500">Lihat riwayat penarikan dana Anda di sini.</p>
            </div>
            <a href="{{ route('user.penarikan.create') }}"
               class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition duration-200 ease-in-out shadow hover:shadow-md">
                <i data-lucide="plus-circle" class="w-5 h-5 mr-2"></i> Buat Penarikan
            </a>
        </div>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="p-4 bg-green-50 text-green-800 rounded-xl border border-green-300 flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Jika tidak ada penarikan --}}
        @if($penarikan->isEmpty())
            <div class="text-center text-gray-500 py-6">
                <i data-lucide="file-search" class="w-8 h-8 mx-auto mb-2 text-gray-400"></i>
                Belum ada riwayat penarikan.
            </div>
        @else
            {{-- Daftar Penarikan --}}
            <div class="space-y-6">
                @foreach($penarikan as $item)
                    @php
                        $status = $item->status;
                        $statusStyle = match($status) {
                            'pending' => 'bg-yellow-500 text-white',
                            'diterima' => 'bg-green-600 text-white',
                            'ditolak' => 'bg-red-600 text-white',
                            default => 'bg-gray-400 text-white',
                        };
                        $statusIcon = match($status) {
                            'pending' => 'clock',
                            'diterima' => 'check-circle',
                            'ditolak' => 'x-circle',
                            default => 'alert-circle',
                        };
                    @endphp

                    <div class="border border-gray-200 rounded-2xl p-6 shadow-sm space-y-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Penarikan #{{ $item->id }}
                                </h3>
                                <p class="text-sm text-gray-500">Tanggal: {{ $item->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <span class="inline-flex items-center {{ $statusStyle }} text-sm font-medium px-3 py-1 rounded-full capitalize gap-1 shadow-sm">
                                <i data-lucide="{{ $statusIcon }}" class="w-4 h-4"></i>
                                {{ $status }}
                            </span>
                        </div>

                        <div class="text-sm text-gray-700 space-y-1">
                            <div class="flex justify-between">
                                <span>Jumlah Penarikan</span>
                                <span class="font-semibold">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Rekening Tujuan</span>
                                <span>{{ $item->rekening_tujuan }}</span>
                            </div>

                            {{-- Bukti Transfer --}}
                            @if($item->bukti_transfer)
                                <div class="flex flex-col gap-2 pt-2">
                                    <span class="text-sm text-gray-600">Bukti Transfer:</span>
                                    <a href="{{ asset('storage/' . $item->bukti_transfer) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $item->bukti_transfer) }}"
                                            alt="Bukti Transfer"
                                            class="w-48 h-auto rounded-lg shadow border hover:opacity-90 transition" />
                                    </a>
                                </div>
                            @endif

                            {{-- Catatan Penolakan --}}
                            @if($item->status === 'ditolak' && $item->catatan)
                                <div class="mt-3 bg-red-50 border border-red-300 text-red-800 p-3 rounded-xl">
                                    <div class="flex items-start gap-2">
                                        <i data-lucide="alert-triangle" class="w-4 h-4 mt-1"></i>
                                        <div>
                                            <p class="text-sm font-medium">Catatan Penolakan:</p>
                                            <p class="text-sm">{{ $item->catatan }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

{{-- Lucide Icon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection
