@extends('layouts.admin')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Transaksi</h2>
                <p class="text-sm text-gray-500">Pantau semua transaksi yang dilakukan oleh pengguna marketplace.</p>
            </div>
            {{-- Search Bar --}}
            <form action="{{ route('admin.transaksi.index') }}" method="GET" class="w-full md:w-auto">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari user atau produk..."
                        value="{{ request('search') }}"
                        class="w-full md:w-72 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-400 text-sm text-gray-700">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </form>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Transaksi --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama User</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Produk</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Total Bayar</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($transaksiList as $transaksi)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $transaksi->id }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $transaksi->user->name }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                @foreach($transaksi->checkout->item as $item)
                                    <div>{{ $item->produk->nama }} <span class="text-gray-500">(x{{ $item->jumlah }})</span></div>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                Rp{{ number_format(optional($transaksi->pembayaran)->total_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium capitalize
                                    @if($transaksi->status === 'diproses') bg-yellow-100 text-yellow-700
                                    @elseif($transaksi->status === 'dikirim') bg-blue-100 text-blue-700
                                    @elseif($transaksi->status === 'selesai') bg-green-100 text-green-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $transaksi->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $transaksi->created_at->format('d M Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-6 text-center text-gray-500">
                                <i data-lucide="package-search" class="w-6 h-6 mx-auto mb-2"></i>
                                Transaksi tidak ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="pt-4">
            {{ $transaksiList->appends(request()->query())->links('pagination::tailwind') }}
        </div>
    </div>
</div>

{{-- Lucide Icon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>
@endsection
