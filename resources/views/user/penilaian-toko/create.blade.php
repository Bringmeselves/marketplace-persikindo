@extends('layouts.app')

@section('title', 'Beri Penilaian')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <i data-lucide="star" class="w-6 h-6 text-yellow-500"></i>
        <h2 class="text-3xl font-bold text-gray-900">
            Beri Penilaian untuk Toko {{ $toko->nama }}
        </h2>
    </div>

    {{-- Pesan Error / Sukses --}}
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded-lg shadow">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded-lg shadow">
            {{ session('success') }}
        </div>
    @endif

    {{-- Form Penilaian --}}
    <form action="{{ route('user.penilaian-toko.store') }}" method="POST">
        @csrf
        <input type="hidden" name="toko_id" value="{{ $toko->id }}">

        <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 space-y-6">
            <div class="flex items-center gap-3 mb-4">
                <i data-lucide="message-square-heart" class="w-5 h-5 text-pink-500"></i>
                <h3 class="text-xl font-semibold text-gray-900">Tulis Penilaian Anda</h3>
            </div>

            {{-- Rating Bintang --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                <div id="star-rating" class="flex gap-1 text-yellow-400 text-2xl cursor-pointer">
                    @for ($i = 1; $i <= 5; $i++)
                        <span class="star {{ old('rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }}" data-value="{{ $i }}">&#9733;</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating" value="{{ old('rating') }}">
                @error('rating')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Ulasan --}}
            <div>
                <label for="ulasan" class="block text-sm font-medium text-gray-700 mb-1">Ulasan (opsional)</label>
                <textarea name="ulasan" id="ulasan" rows="4"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('ulasan') }}</textarea>
                @error('ulasan')
                    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            {{-- Tombol --}}
            <div class="pt-4 flex gap-3">
                <a href="{{ route('user.transaksi.index') }}"
                   class="w-full flex items-center justify-center gap-2 bg-gray-200 text-gray-700 py-3 rounded-xl font-semibold hover:bg-gray-300 transition duration-300 shadow hover:shadow-lg">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                    Batal
                </a>
                <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition duration-300 shadow hover:shadow-lg">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    Kirim Penilaian
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Lucide & Star Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        const stars = document.querySelectorAll('.star');
        const ratingInput = document.getElementById('rating');

        function highlightStars(rating) {
            stars.forEach((star, index) => {
                star.classList.toggle('text-yellow-400', index < rating);
                star.classList.toggle('text-gray-300', index >= rating);
            });
        }

        stars.forEach(star => {
            star.addEventListener('mouseover', () => {
                highlightStars(parseInt(star.dataset.value));
            });

            star.addEventListener('mouseleave', () => {
                highlightStars(parseInt(ratingInput.value) || 0);
            });

            star.addEventListener('click', () => {
                ratingInput.value = star.dataset.value;
                highlightStars(parseInt(star.dataset.value));
            });
        });

        highlightStars(parseInt(ratingInput.value) || 0);
    });
</script>
@endsection
