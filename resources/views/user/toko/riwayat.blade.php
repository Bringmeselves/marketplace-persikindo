@extends('layouts.app')

@section('title', 'Riwayat Transaksi Toko')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Riwayat Transaksi Toko</h2>
                <p class="text-sm text-gray-500">Lihat semua transaksi untuk produk yang Anda jual di toko ini.</p>
            </div>
        </div>

        {{-- Tabel Transaksi --}}
        @if ($transaksiList->isEmpty())
            <div class="text-center text-gray-500 py-10">
                <i data-lucide="package-search" class="w-6 h-6 mx-auto mb-2"></i>
                Belum ada transaksi untuk produk yang Anda jual.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Pembeli</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Produk</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach ($transaksiList as $transaksi)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900">#{{ $transaksi->id }}</td>
                                <td class="px-6 py-4 text-gray-800">{{ $transaksi->checkout->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $transaksi->produk->nama ?? '-' }}</td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="pt-4">
                {{ $transaksiList->links('pagination::tailwind') }}
            </div>
        @endif
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
