@extends('layouts.admin')

@section('title', 'Daftar Kategori')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Kategori</h2>
            <p class="text-sm text-gray-500">Kelola kategori produk dan jasa di sistem marketplace.</p>
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

        {{-- Tombol Tambah --}}
        <div class="flex justify-end">
            <a href="{{ route('admin.kategori.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Kategori
            </a>
        </div>

        {{-- Tabel Kategori --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Slug</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($kategori as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $item->slug }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                 {{-- Tombol Edit --}}
                                <form action="{{ route('admin.kategori.edit', $item->id) }}" method="GET">
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-yellow-600 transition">
                                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i> Edit
                                    </button>
                                </form>

                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn-delete inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 transition"
                                            data-name="{{ $item->name }}">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                                <i data-lucide="folder-open" class="w-6 h-6 mx-auto mb-2"></i>
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi jika pakai pagination --}}
        @if(method_exists($kategori, 'links'))
            <div>{{ $kategori->links('pagination::tailwind') }}</div>
        @endif

    </div>
</div>

{{-- SweetAlert & Ikon --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();

        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const kategoriName = this.dataset.name;

                Swal.fire({
                    title: 'Yakin hapus kategori?',
                    text: `Kategori "${kategoriName}" akan dihapus secara permanen.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
