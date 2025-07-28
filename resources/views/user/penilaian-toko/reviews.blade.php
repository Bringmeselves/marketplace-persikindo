@extends('layouts.app')

@section('title', 'Ulasan Toko')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-8 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">
                Ulasan untuk {{ $toko->nama_toko}}
            </h2>
            <p class="text-sm text-gray-500">
                Total Ulasan: {{ $totalReview }}
            </p>
        </div>

        {{-- Daftar Ulasan --}}
        <div class="space-y-4">
            @forelse($reviews as $review)
                @php
                    $userImage = 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name);
                @endphp

                <div class="flex items-start gap-4 p-4 rounded-xl bg-white border hover:shadow-md transition">
                    {{-- Avatar --}}
                    <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0 border">
                        <img src="{{ $userImage }}" alt="{{ $review->user->name }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Review Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-base font-semibold text-gray-900 truncate">
                                {{ $review->user->name }}
                            </p>
                            <span class="text-xs text-gray-400 whitespace-nowrap">
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Rating --}}
                        <div class="flex items-center mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>

                        {{-- Ulasan --}}
                        <p class="mt-2 text-gray-700">
                            {{ $review->ulasan }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 italic mt-8">
                    Belum ada ulasan untuk toko ini.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>

        {{-- Tombol Kembali --}}
        <div class="mt-6">
            <a href="{{ route('user.toko.show', $toko->id) }}"
               class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
               ← Kembali ke Toko
            </a>
        </div>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
