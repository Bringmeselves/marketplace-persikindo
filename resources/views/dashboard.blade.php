@extends('layouts.app')

@section('content')

    {{-- Alert Welcome --}}
    @if (session('welcome'))
        <div class="mb-6 p-4 bg-blue-100 text-blue-800 border border-blue-300 rounded shadow">
            {{ session('welcome') }}
        </div>
    @endif

    
    {{-- Error --}}
    @if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Hero Section --}}
    <div class="relative w-full h-[70vh] mb-10 overflow-hidden rounded-lg">
        <img src="{{ asset('images/persikindo1.jpg') }}" alt="Hero" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white">PERSIKINDO</h1>
                <p class="text-xl text-white">Perkumpulan Srikandi Kreatif Indonesia</p>
            </div>
        </div>
    </div>

    {{-- Alert Success --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tentang Kami --}}
    <section class="mb-16 bg-gray-100 py-12 px-6 rounded-lg">
        <div class="max-w-4xl mx-auto">
            <h3 class="text-2xl font-bold mb-4 text-center text-gray-800">Tentang Kami</h3>
            <p class="text-gray-700 leading-relaxed text-center">
                PERSIKINDO (Perkumpulan Srikandi Kreatif Indonesia) merupakan organisasi kemasyarakatan berbadan hukum yang memiliki komitmen untuk memberdayakan perempuan Indonesia, khususnya di bidang ekonomi kreatif serta pengembangan Usaha Mikro, Kecil, dan Menengah (UMKM). Didirikan pada tahun 2022, PERSIKINDO hadir sebagai wadah bagi perempuan pelaku usaha untuk meningkatkan kapasitas, kreativitas, dan kemandirian ekonomi melalui berbagai program pelatihan, pendampingan, fasilitasi akses permodalan, serta perluasan jejaring pasar. Dengan kepengurusan yang tersebar di 34 provinsi, PERSIKINDO terus tumbuh dan berperan sebagai mitra strategis bagi pemerintah maupun sektor swasta dalam mendukung pertumbuhan ekonomi berbasis potensi lokal dan peran aktif perempuan. Melalui berbagai inisiatifnya, PERSIKINDO bertekad menjadi penggerak bagi perempuan Indonesia yang produktif, inovatif, dan berdaya saing.
            </p>
        </div>
    </section>

    {{-- Visi dan Misi --}}
    <section class="mb-16 bg-white py-12 px-6 rounded-lg shadow-sm">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-10">
            <div>
                <h3 class="text-xl font-bold mb-2 text-blue-700">Visi</h3>
                <p class="text-gray-700">Menjunjung tinggi harkat dan martabat wanita Indonesia yang berwawasan ekonomi Pancasila.</p>
            </div>
            <div>
                <h3 class="text-xl font-bold mb-2 text-blue-700">Misi</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    <li>Memberikan pelatihan dan pendampingan untuk meningkatkan kapasitas anggota dalam mengelola usaha.</li>
                    <li>Memfasilitasi akses permodalan dan teknologi baru bagi anggota.</li>
                    <li>Meningkatkan produktivitas dan kualitas produk anggota.</li>
                </ul>
            </div>
        </div>
    </section>

    {{-- Program Kami --}}
    <section
        class="mb-16 relative py-12 px-6 rounded-lg"
        style="background-image: url('{{ asset('images/persikindo2.jpg') }}'); background-size: cover; background-position: center;"
    >
        <div class="max-w-6xl mx-auto bg-blue-50 bg-opacity-80 rounded-lg p-8 relative z-10">
            <h3 class="text-2xl font-bold mb-6 text-center text-blue-800">Program Unggulan</h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-lg mb-2 text-blue-700">Pelatihan Kewirausahaan</h4>
                    <p class="text-gray-600">Memberi pelatihan bisnis kecil untuk pemuda lokal.</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-lg mb-2 text-blue-700">Bakti Sosial</h4>
                    <p class="text-gray-600">Kegiatan sosial di desa terpencil setiap bulan.</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <h4 class="font-semibold text-lg mb-2 text-blue-700">Forum Diskusi</h4>
                    <p class="text-gray-600">Tempat berbagi ide dan solusi kreatif antar anggota.</p>
                </div>
            </div>
        </div>
    </section>


    {{-- Kontak --}}
    <section class="mb-16 bg-white-100 py-10 px-6 rounded-lg max-w-3xl mx-auto text-center">
        <h3 class="text-2xl font-semibold mb-6 text-gray-800">Hubungi Kami</h3>
        <div class="space-y-5 text-gray-700">
            <a href="mailto:info@organisasi.com" class="flex items-center justify-center space-x-2 hover:text-blue-600 transition-colors">
            📧
            <span class="font-medium">info@organisasi.com</span>
            </a>
            <div class="flex items-center justify-center space-x-2">
            📍
            <span class="font-medium">Jl. Merdeka No. 123, Jakarta</span>
            </div>
            <a href="https://instagram.com/organisasi_kita" target="_blank" class="flex items-center justify-center space-x-2 hover:text-pink-600 transition-colors">
            📸
            <span class="font-medium">@organisasi_kita</span>
            </a>
        </div>
    </section>

@endsection
