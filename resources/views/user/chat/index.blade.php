@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-8 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ $toko ? 'Chat dari Pembeli' : 'Chat ke Toko' }}
            </h2>
            <p class="text-sm text-gray-500">
                {{ $toko ? 'Lihat daftar pesan masuk dari pembeli.' : 'Lihat daftar toko yang pernah Anda hubungi.' }}
            </p>
        </div>

        {{-- Daftar Chat --}}
        <div class="space-y-4">
            @forelse($daftarChat as $chat)
                <div class="p-4 rounded-xl bg-white border shadow-sm hover:shadow-md transition flex items-center justify-between">
                    <div class="space-y-1">
                        @if($toko)
                            <p class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                                {{ $chat->user->name }}
                            </p>
                        @else
                            <p class="text-base font-semibold text-gray-900 flex items-center gap-2">
                                <i data-lucide="store" class="w-4 h-4 text-gray-500"></i>
                                {{ $chat->toko->nama_toko }}
                            </p>
                        @endif
                        <p class="text-sm text-gray-600">
                            Pesan terakhir: {{ optional($chat->pesan->last())->isi_pesan ?? 'Belum ada pesan' }}
                        </p>
                    </div>

                    <a href="{{ route('user.chat.tampil', $chat->id) }}">
                        <button type="button"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 text-sm font-semibold shadow-sm">
                            <i data-lucide="message-circle" class="w-4 h-4"></i> Buka Chat
                        </button>
                    </a>
                </div>
            @empty
                <div class="text-center text-gray-500 italic mt-8">
                    {{ $toko ? 'Belum ada chat dari pembeli.' : 'Kamu belum mengirim chat ke toko mana pun.' }}
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
@endsection
