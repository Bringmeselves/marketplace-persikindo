@extends('layouts.admin')

@section('title', 'Daftar Kategori')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">Daftar Kategori</h2>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
            <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
            <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Tombol Tambah --}}
    <div>
        <a href="{{ route('admin.kategori.create') }}" 
           class="inline-flex items-center gap-2 px-5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
            <i data-lucide="plus" class="w-4 h-4"></i>
            Tambah Kategori
        </a>
    </div>

    {{-- Tabel Kategori --}}
    <div class="overflow-x-auto bg-white shadow rounded-xl mt-6">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 border-b">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Slug</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategori as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ $item->name }}</td>
                        <td class="px-6 py-4">{{ $item->slug }}</td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <a href="{{ route('admin.kategori.edit', $item->id) }}" 
                               class="inline-flex items-center gap-1 px-4 py-1.5 rounded-lg bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-semibold transition">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                                Edit
                            </a>
                            <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
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
                        <td colspan="4" class="text-center py-10 text-gray-400 italic">
                            <i data-lucide="folder-open" class="w-6 h-6 mx-auto mb-2"></i>
                            Belum ada kategori yang ditambahkan.
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
