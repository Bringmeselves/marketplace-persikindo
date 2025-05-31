@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow-md p-8 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Anggota</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <p class="text-gray-700 mb-4">
        Selamat datang <strong>{{ auth()->user()->name }}</strong>, Anda telah disetujui sebagai <strong>anggota resmi</strong> Persikindo.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-blue-50 p-4 rounded shadow hover:shadow-lg transition">
            <h2 class="text-lg font-semibold text-blue-700 mb-2">Kelola Produk</h2>
            <p class="text-sm text-gray-600">Lihat dan kelola daftar produk yang Anda jual.</p>
            <a href="{{ route('user.produk.index') }}" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                Lihat Produk
            </a>
        </div>

        <div class="bg-green-50 p-4 rounded shadow hover:shadow-lg transition">
            <h2 class="text-lg font-semibold text-green-700 mb-2">Kelola Toko</h2>
            <p class="text-sm text-gray-600">Atur informasi toko Anda sebagai anggota.</p>
            <a href="{{ route('user.toko.create') }}" class="inline-block mt-3 px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                Kelola Toko
            </a>
        </div>
    </div>
</div>
@endsection
