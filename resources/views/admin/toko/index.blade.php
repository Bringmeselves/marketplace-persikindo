@extends('layouts.admin')

@section('title', 'Daftar Toko')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Daftar Toko</h1>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">No</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama Toko</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Pemilik</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nama Anggota</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($toko as $index => $item)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nama_toko }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->user->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->user->email ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->user->anggota->nama_lengkap ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('admin.toko.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus toko ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data toko.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
