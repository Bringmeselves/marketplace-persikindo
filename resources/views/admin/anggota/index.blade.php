@extends('layouts.admin')

@section('title', 'Daftar Anggota')

@section('content')
<div class="container mx-auto max-w-screen-xl px-4 mt-4">
    <h1 class="text-2xl font-bold mb-6">Daftar Anggota</h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <!-- Grid Layout untuk Anggota -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($anggota as $anggotaItem)
            <div class="bg-white p-4 border rounded-lg shadow hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-1">{{ $anggotaItem->user->name }}</h3>
                <p class="text-sm text-gray-500 mb-1">{{ $anggotaItem->user->email }}</p>
                <p class="text-sm font-medium mb-1 {{ 
                    $anggotaItem->status == 'approved' ? 'text-green-600' : 
                    ($anggotaItem->status == 'rejected' ? 'text-red-600' : 'text-yellow-600') 
                }}">{{ ucfirst($anggotaItem->status) }}</p>
                <p class="text-xs text-gray-400 mb-4">{{ $anggotaItem->created_at->format('d M Y') }}</p>
                
                <div class="flex flex-wrap gap-2">
                    <form action="{{ route('admin.anggota.show', $anggotaItem->id) }}" method="GET">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white text-xs px-3 py-1 rounded hover:bg-blue-600">Lihat</button>
                    </form>
                    <form action="{{ route('admin.anggota.verify', $anggotaItem->id) }}" method="POST" onsubmit="return confirm('Yakin ingin memverifikasi anggota ini?')">
                        @csrf
                        <button type="submit" class="bg-green-500 text-white text-xs px-3 py-1 rounded hover:bg-green-600">Verifikasi</button>
                    </form>
                    <form action="{{ route('admin.anggota.reject', $anggotaItem->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak pendaftaran ini?')">
                        @csrf
                        <button type="submit" class="bg-yellow-500 text-white text-xs px-3 py-1 rounded hover:bg-yellow-600">Tolak</button>
                    </form>
                    <form action="{{ route('admin.anggota.destroy', $anggotaItem->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white text-xs px-3 py-1 rounded hover:bg-red-600">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="col-span-full text-center text-gray-500">Belum ada data anggota.</p>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8 flex justify-center">
        {{ $anggota->links() }}
    </div>
</div>
@endsection
