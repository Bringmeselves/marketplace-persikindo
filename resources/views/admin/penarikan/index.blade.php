@extends('layouts.admin')

@section('title', 'Daftar Penarikan Saldo')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Penarikan Saldo</h2>
                <p class="text-sm text-gray-500">Kelola dan tinjau permintaan penarikan saldo oleh toko.</p>
            </div>
            {{-- Search Bar --}}
            <form action="{{ route('admin.penarikan.index') }}" method="GET" class="w-full md:w-auto">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari toko..."
                        value="{{ request('search') }}"
                        class="w-full md:w-72 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-400 text-sm text-gray-700">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </form>
        </div>


        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tabel Penarikan --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Toko</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($penarikan as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->toko->nama_toko }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium capitalize
                                    @if($item->status === 'diproses') bg-yellow-100 text-yellow-700
                                    @elseif($item->status === 'selesai') bg-green-100 text-green-700
                                    @elseif($item->status === 'ditolak') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.penarikan.show', $item->id) }}"
                                   class="inline-flex items-center text-blue-600 hover:underline text-sm font-medium">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500">Belum ada permintaan penarikan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi halaman jika ada --}}
        <div>
            {{ $penarikan->links('pagination::tailwind') }}
        </div>
    </div>
</div>

{{-- Ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
