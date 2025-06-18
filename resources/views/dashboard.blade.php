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
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700 flex-shrink-0 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4 -4" />
                        </svg>
                        {{ $misi }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

{{-- PROGRAM UNGGULAN --}}
@php
    // Data program dengan icon lucide nama
    $programs = [
        ['title' => 'Pelatihan Kewirausahaan', 'desc' => 'Pelatihan bisnis kecil untuk pemuda lokal.', 'icon' => 'briefcase'],
        ['title' => 'Bakti Sosial', 'desc' => 'Kegiatan sosial bulanan di desa terpencil.', 'icon' => 'heart-handshake'],
        ['title' => 'Forum Diskusi', 'desc' => 'Wadah berbagi ide dan solusi kreatif antar anggota.', 'icon' => 'message-circle'],
    ];

    // Fungsi untuk menampilkan SVG icon lucide secara manual
    function lucide_icon($name, $class = 'w-12 h-12 mx-auto mb-6 text-gray-700 animate-pulse') {
        $icons = [
            'briefcase' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7V3m0 0L7 7m5-4l5 4M5 7h14a2 2 0 012 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2v-7a2 2 0 012-2z"/></svg>',
            'heart-handshake' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.828 9.172a4 4 0 10-5.656 5.656l1.414-1.414a1 1 0 011.414 0l3.536 3.536a1 1 0 001.414-1.414l-3.536-3.536a1 1 0 010-1.414l1.414-1.414z"/></svg>',
            'message-circle' => '<svg xmlns="http://www.w3.org/2000/svg" class="'.$class.'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 11-3.8-11.4 8.38 8.38 0 013.8.9L21 3l-3.5 3.5"/></svg>',
        ];

        return $icons[$name] ?? '';
    }
@endphp

<section class="mb-20 relative py-20 px-6 bg-gray-50 rounded-3xl shadow-sm">
    <div class="bg-white rounded-3xl max-w-7xl mx-auto px-6 py-16 shadow-md">
        <h2 class="text-4xl font-extrabold text-center mb-16 tracking-wide text-gray-800">Program Unggulan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-14 max-w-5xl mx-auto">
            @foreach ($programs as $program)
                <div class="flex flex-col items-center text-center p-6 rounded-3xl border border-gray-300 shadow-sm hover:shadow-lg transition-shadow duration-300">
                    {!! lucide_icon($program['icon']) !!}
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $program['title'] }}</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $program['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
