@extends('layouts.app')

@section('content')

{{-- ALERT --}}
@if (session('welcome') || session('error') || session('success'))
    <div class="max-w-5xl mx-auto mb-8 space-y-4 px-4">
        @if (session('welcome'))
            <div class="p-4 rounded-lg bg-gray-100 text-gray-800 shadow">
                {{ session('welcome') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 rounded-lg bg-red-100 text-red-800 shadow">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="p-4 rounded-lg bg-green-100 text-green-800 shadow">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endif

{{-- HERO SLIDER --}}
<section 
    x-data="{
        currentSlide: 0,
        slides: [
            { image: '{{ asset('images/persikindo1.jpg') }}', title: 'PERSIKINDO', subtitle: 'Perkumpulan Srikandi Kreatif Indonesia' },
            { image: '{{ asset('images/persikindo2.jpg') }}', title: 'Pemberdayaan UMKM', subtitle: 'Kami hadir untuk mendampingi usaha Anda tumbuh' },
            { image: '{{ asset('images/persikindo3.jpeg') }}', title: 'Perempuan Berkarya', subtitle: 'Memberi dampak nyata untuk ekonomi lokal' },
        ],
        init() {
            setInterval(() => {
                this.currentSlide = (this.currentSlide + 1) % this.slides.length;
            }, 5000);
        }
    }"
    x-init="init"
    class="relative w-full h-[75vh] mb-20 overflow-hidden rounded-3xl shadow-md"
>
    <template x-for="(slide, index) in slides" :key="index">
        <div
            x-show="currentSlide === index"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 scale-105"
            x-transition:enter-end="opacity-100 scale-100"
            class="absolute inset-0 w-full h-full"
        >
            <img :src="slide.image" alt="Hero Image" class="w-full h-full object-cover" />
            
            {{-- Overlay dan Konten --}}
            <div class="absolute inset-0 bg-black/20 flex items-center justify-center text-center px-6">
                <div class="max-w-3xl">
                    <h1 class="text-5xl font-extrabold tracking-wide mb-4 text-white" x-text="slide.title"></h1>
                    <p class="text-xl md:text-2xl font-medium mb-6 text-white" x-text="slide.subtitle"></p>
                </div>
            </div>
        </div>
    </template>
</section>


    {{-- Manual Navigation --}}
    <div class="absolute bottom-5 left-1/2 transform -translate-x-1/2 flex gap-2">
        <template x-for="(slide, index) in slides" :key="index">
            <button 
                @click="currentSlide = index"
                class="w-3 h-3 rounded-full"
                :class="currentSlide === index ? 'bg-white' : 'bg-white/40'"
            ></button>
        </template>
    </div>
</section>

{{-- TENTANG KAMI --}}
<section class="mb-20 py-16 px-6 bg-white rounded-3xl shadow-sm">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="text-4xl font-bold text-gray-800 mb-6 tracking-tight flex items-center justify-center gap-3">
            <svg class="w-8 h-8 text-gray-700" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 2a6 6 0 00-6 6c0 4 6 10 6 10s6-6 6-10a6 6 0 00-6-6z" />
            </svg>
            Tentang Kami
        </h2>
        <p class="text-gray-700 text-lg leading-relaxed">
            <span class="font-semibold text-gray-800">PERSIKINDO</span> adalah organisasi perempuan Indonesia yang fokus pada pemberdayaan ekonomi kreatif dan UMKM. Berdiri sejak 2022, kami mendukung para pelaku usaha melalui pelatihan, pendampingan, akses permodalan, dan ekspansi pasar.
            <br><br>
            Dengan kepengurusan di 34 provinsi, PERSIKINDO menjadi mitra strategis dalam mendorong pertumbuhan ekonomi lokal berbasis perempuan.
        </p>
    </div>
</section>

{{-- VISI & MISI --}}
<section class="mb-20 py-16 px-6 bg-gray-50 rounded-3xl shadow-sm">
    <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
        <div>
            <h3 class="text-3xl font-semibold text-gray-800 mb-4">Visi</h3>
            <p class="text-gray-700 text-lg leading-relaxed">
                Menjunjung tinggi harkat dan martabat wanita Indonesia yang berwawasan ekonomi Pancasila.
            </p>
        </div>
        <div>
            <h3 class="text-3xl font-semibold text-gray-800 mb-4">Misi</h3>
            <ul class="text-gray-700 space-y-3 text-lg">
                @foreach ([
                    'Pelatihan dan pendampingan usaha untuk anggota.',
                    'Akses permodalan dan teknologi baru.',
                    'Peningkatan kualitas dan produktivitas produk.'
                ] as $misi)
                    <li class="flex gap-2 items-start">
                        <svg class="w-6 h-6 text-gray-700 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $misi }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- PROGRAM UNGGULAN --}}
<section class="mb-20 relative py-20 px-6 bg-gray-50 rounded-3xl shadow-sm">
    <div class="bg-white rounded-3xl max-w-6xl mx-auto p-10 shadow-sm">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-12 tracking-tight">Program Unggulan</h2>
        <div class="grid md:grid-cols-3 gap-10">
            @php
                $programs = [
                    ['title' => 'Pelatihan Kewirausahaan', 'desc' => 'Pelatihan bisnis kecil untuk pemuda lokal.', 'icon' => '💼'],
                    ['title' => 'Bakti Sosial', 'desc' => 'Kegiatan sosial bulanan di desa terpencil.', 'icon' => '🤝'],
                    ['title' => 'Forum Diskusi', 'desc' => 'Wadah berbagi ide dan solusi kreatif antar anggota.', 'icon' => '🗣️'],
                ];
            @endphp
            @foreach ($programs as $item)
                <div class="bg-white border border-gray-100 rounded-2xl p-8 text-center shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out">
                    <div class="text-5xl mb-6 animate-pulse">{{ $item['icon'] }}</div>
                    <h4 class="text-2xl font-semibold text-gray-800 mb-3">{{ $item['title'] }}</h4>
                    <p class="text-gray-600 text-base leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- KONTAK --}}
<section class="mb-20 py-16 px-6 max-w-3xl mx-auto bg-white rounded-3xl shadow text-center">
    <h2 class="text-3xl font-bold text-gray-800 mb-8 tracking-tight">Hubungi Kami</h2>
    <div class="space-y-6 text-gray-700 text-lg">
        <a href="mailto:info@organisasi.com" class="flex items-center justify-center gap-3 hover:underline font-medium">
            <span>📧</span> info@organisasi.com
        </a>
        <div class="flex items-center justify-center gap-3 font-medium">
            <span>📍</span> Jl. Merdeka No. 123, Jakarta
        </div>
        <a href="https://instagram.com/organisasi_kita" target="_blank" class="flex items-center justify-center gap-3 hover:underline font-medium">
            <span>📸</span> @organisasi_kita
        </a>
    </div>
</section>

@endsection
