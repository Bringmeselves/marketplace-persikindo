@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-6">

    <h2 class="text-2xl font-bold text-gray-900">
        {{ $toko ? 'Chat dari Pembeli' : 'Chat ke Toko' }}
    </h2>

    @forelse($daftarChat as $chat)
        <div class="p-4 border rounded-xl bg-white shadow-sm flex items-center justify-between hover:shadow transition">
            <div>
                @if($toko)
                    {{-- Sebagai Penjual: tampilkan nama pembeli --}}
                    <p class="font-medium text-gray-800 flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                        {{ $chat->user->name }}
                    </p>
                @else
                    {{-- Sebagai Pembeli: tampilkan nama toko --}}
                    <p class="font-medium text-gray-800 flex items-center gap-2">
                        <i data-lucide="store" class="w-4 h-4 text-gray-500"></i>
                        {{ $chat->toko->nama_toko }}
                    </p>
                @endif

                <p class="text-sm text-gray-600">
                    Pesan terakhir:
                    {{ optional($chat->pesan->last())->isi_pesan ?? 'Belum ada pesan' }}
                </p>
            </div>
            <a href="{{ route('user.chat.tampil', $chat->id) }}">
                <button type="button"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">
                    <i data-lucide="message-circle" class="w-4 h-4"></i>
                    Buka Chat
                </button>
            </a>
        </div>
    @empty
        <div class="text-gray-500 italic text-center mt-12">
            {{ $toko ? 'Belum ada chat dari pembeli.' : 'Kamu belum mengirim chat ke toko mana pun.' }}
        </div>
    @endforelse

</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons();</script>
@endsection
