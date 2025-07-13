@extends('layouts.admin')

@section('title', 'Daftar Produk')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Daftar Produk</h2>
                <p class="text-sm text-gray-500">Kelola produk yang ditambahkan oleh anggota marketplace.</p>
            </div>
            {{-- Search Bar --}}
            <form action="{{ route('admin.produk.index') }}" method="GET" class="w-full md:w-auto">
                <div class="relative">
                    <input type="text" name="search" placeholder="Cari produk..."
                        value="{{ request('search') }}"
                        class="w-full md:w-72 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:border-blue-400 text-sm text-gray-700">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                </div>
            </form>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-200 text-green-700 text-sm rounded-lg px-4 py-3">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabel Produk --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">No</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Gambar</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama Produk</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Pemilik</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Toko</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Kategori</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Harga</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($produk as $index => $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($item->gambar)
                                    <img src="{{ asset('produk/' . $item->gambar) }}" alt="{{ $item->nama }}" class="w-12 h-12 object-cover rounded-md">
                                @else
                                    <div class="w-12 h-12 bg-gray-100 flex items-center justify-center rounded-md text-gray-400 text-xs">No Image</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $item->nama }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->user->anggota->nama_lengkap ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->toko->nama_toko ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->kategori->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.produk.destroy', $item->id) }}" method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn-delete inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 transition"
                                            data-name="{{ $item->nama }}">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-6 text-center text-gray-500">
                                <i data-lucide="folder-open" class="w-6 h-6 mx-auto mb-2"></i>
                                Belum ada produk yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi jika pakai pagination --}}
        @if(method_exists($produk, 'links'))
            <div>{{ $produk->links('pagination::tailwind') }}</div>
        @endif

    </div>
</div>

{{-- SweetAlert & Lucide Icons --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();

        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const produkName = this.dataset.name;

                Swal.fire({
                    title: 'Yakin hapus produk?',
                    text: `Produk "${produkName}" akan dihapus secara permanen.`,
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
