@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
<script>
    setInterval(() => {
        window.location.reload();
    }, 10000); // reload tiap 10 detik
</script>

<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-8 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Riwayat Chat</h2>
            <p class="text-sm text-gray-500">Lihat daftar pesan dengan toko dan pembeli yang pernah Anda hubungi.</p>
        </div>

        {{-- Daftar Chat --}}
        <div class="space-y-4">
            @forelse($daftarChat as $chat)
                @php
                    $isSender = $chat->user_id === auth()->id();
                    $targetName = $isSender ? $chat->toko->nama_toko : $chat->user->name;
                    $targetImage = $isSender
                        ? ($chat->toko->foto_toko ? asset('storage/' . $chat->toko->foto_toko) : 'https://via.placeholder.com/150')
                        : 'https://ui-avatars.com/api/?name=' . urlencode($chat->user->name);

                    $lastMessage = $chat->pesan->first();
                    $isUnread = $lastMessage && !$lastMessage->sudah_dibaca && $lastMessage->user_id !== auth()->id();

                    // Hitung pesan yang belum dibaca dari lawan bicara
                    $jumlahBelumDibaca = $chat->pesanBelumDibaca->where('user_id', '!=', auth()->id())->count();
                @endphp

                <a href="{{ route('user.chat.tampil', $chat->id) }}"
                   class="flex items-center justify-between p-4 rounded-xl bg-white border hover:shadow-md transition gap-4">

                    {{-- Avatar --}}
                    <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 border">
                        <img src="{{ $targetImage }}" alt="{{ $targetName }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Chat Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-semibold text-gray-900 truncate">
                            {{ $targetName }}
                        </p>
                        <div class="flex items-center text-sm text-gray-600 gap-1">
                            @if($lastMessage)
                                <span class="truncate">{{ $lastMessage->isi_pesan }}</span>
                                {{-- Centang satu / dua --}}
                                @if($lastMessage->user_id === auth()->id())
                                    <i data-lucide="{{ $lastMessage->sudah_dibaca ? 'check-check' : 'check' }}"
                                       class="w-4 h-4 text-green-500"></i>
                                @endif
                            @else
                                <span class="italic text-gray-400">Belum ada pesan</span>
                            @endif
                        </div>
                    </div>

                    {{-- Notifikasi & waktu --}}
                    <div class="flex flex-col items-end justify-between">
                        @if($jumlahBelumDibaca > 0)
                            <span class="text-xs bg-red-500 text-white px-2 py-0.5 rounded-full mb-1">
                                {{ $jumlahBelumDibaca }}
                            </span>
                        @endif
                        <span class="text-xs text-gray-400 whitespace-nowrap">
                            @if($lastMessage)
                                {{ $lastMessage->created_at->diffForHumans() }}
                            @endif
                        </span>
                    </div>
                </a>
            @empty
                <div class="text-center text-gray-500 italic mt-8">
                    Tidak ada riwayat chat.
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
<script>
    // Auto refresh setiap 10 detik
    setInterval(() => {
        window.location.reload();
    }, 10000); // 10000ms = 10 detik
</script>
@endsection
