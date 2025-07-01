@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">Daftar Produk</h2>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tabel Produk --}}
    <div class="overflow-x-auto bg-white shadow rounded-xl mt-6">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Gambar</th>
                    <th class="px-6 py-4">Nama Produk</th>
                    <th class="px-6 py-4">Pemilik</th>
                    <th class="px-6 py-4">Toko</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Harga</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($produk as $index => $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>
                        <td class="px-6 py-4">
                            @if($item->gambar)
                                <img src="{{ asset('produk/' . $item->gambar) }}" alt="{{ $item->nama }}" class="w-12 h-12 object-cover rounded-md">
                            @else
                                <div class="w-12 h-12 bg-gray-100 flex items-center justify-center rounded-md text-gray-400 text-xs">No Image</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $item->nama }}</td>
                        <td class="px-6 py-4">{{ $item->user->anggota->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item->toko->nama_toko ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $item->kategori->name ?? '-' }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <form action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-4 py-1.5 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-400 italic">
                            <i data-lucide="folder-open" class="w-6 h-6 mx-auto mb-2"></i>
                            Belum ada produk yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
