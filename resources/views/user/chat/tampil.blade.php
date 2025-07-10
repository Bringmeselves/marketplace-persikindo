@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">

    {{-- Judul Halaman --}}
    <div class="flex items-center justify-between pb-4 border-b">
        <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
            <i data-lucide="message-circle" class="w-6 h-6 text-green-600"></i>
            Chat dengan: {{ $chat->toko->nama_toko }}
        </h2>
        <a href="{{ route('user.chat.index') }}"
           class="inline-flex items-center gap-2 text-sm font-semibold text-green-600 hover:underline">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>

    {{-- Daftar Pesan --}}
    <div class="bg-white border rounded-2xl shadow p-4 space-y-2 max-h-[400px] overflow-y-auto">
        @forelse($chat->pesan as $pesan)
            @php
                $isMe = auth()->id() === $pesan->user_id;
            @endphp
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] p-3 rounded-xl text-sm shadow
                            {{ $isMe ? 'bg-green-500 text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                    <div class="mb-1 font-semibold text-xs opacity-80 flex items-center gap-1">
                        <i data-lucide="user" class="w-3 h-3"></i>
                        {{ $pesan->user->name }}
                    </div>
                    <div>{{ $pesan->isi_pesan }}</div>
                    <div class="mt-2 text-[11px] text-right {{ $isMe ? 'text-white/70' : 'text-gray-500' }}">
                        {{ $pesan->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        @empty
            <p class="text-sm text-center text-gray-500">Belum ada pesan dalam percakapan ini.</p>
        @endforelse
    </div>

    {{-- Form Kirim Pesan --}}
    <form action="{{ route('user.kirimPesan', $chat->id) }}" method="POST"
          class="bg-white border rounded-2xl shadow p-4 flex items-center gap-2">
        @csrf
        <div class="relative flex-1">
            <textarea name="isi_pesan"
                      class="w-full border border-gray-300 rounded-full py-2 pl-4 pr-10 resize-none focus:outline-none focus:ring-2 focus:ring-green-400 text-sm"
                      rows="1" required placeholder="Tulis pesan..."></textarea>
            <div class="absolute right-3 top-2.5 text-gray-400">
                <i data-lucide="pen-line" class="w-4 h-4"></i>
            </div>
        </div>
        <button type="submit"
                class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white hover:bg-green-600 shadow">
            <i data-lucide="send" class="w-4 h-4"></i>
        </button>
    </form>
</div>

{{-- Inisialisasi Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
