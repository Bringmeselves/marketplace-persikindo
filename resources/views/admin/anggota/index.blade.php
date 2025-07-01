@extends('layouts.admin')

@section('title', 'Daftar Anggota')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">
    {{-- Judul Halaman --}}
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Daftar Anggota</h2>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-4 rounded-xl shadow">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-4 rounded-xl shadow">{{ session('error') }}</div>
    @endif

    {{-- Jika tidak ada anggota --}}
    @if ($anggota->isEmpty())
        <div class="bg-white shadow-lg rounded-2xl p-6 text-gray-600">
            Belum ada data anggota.
        </div>
    @else
        {{-- Daftar Anggota --}}
        <div class="space-y-6">
            @foreach ($anggota as $anggotaItem)
                <div class="bg-white shadow-lg rounded-2xl p-6 space-y-4">
                    {{-- Header Anggota --}}
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-900">{{ $anggotaItem->user->name }}</h3>
                        <span class="text-sm px-3 py-1 rounded-full 
                            {{ 
                                $anggotaItem->status === 'approved' ? 'bg-green-100 text-green-700' : 
                                ($anggotaItem->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') 
                            }}">
                            {{ ucfirst($anggotaItem->status) }}
                        </span>
                    </div>

                    {{-- Detail Info --}}
                    <div class="text-sm space-y-1">
                        <p>Email: <span class="text-gray-700">{{ $anggotaItem->user->email }}</span></p>
                        <p>Terdaftar: <span class="text-gray-600">{{ $anggotaItem->created_at->format('d M Y') }}</span></p>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex flex-wrap gap-2 pt-2 justify-end">
                        <a href="{{ route('admin.anggota.show', $anggotaItem->id) }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-100 text-blue-800 hover:bg-blue-200 text-sm font-semibold">
                            <i data-lucide="eye" class="w-4 h-4"></i> Lihat
                        </a>
                        <form action="{{ route('admin.anggota.verify', $anggotaItem->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin memverifikasi anggota ini?')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 text-green-800 hover:bg-green-200 text-sm font-semibold">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Verifikasi
                            </button>
                        </form>
                        <form action="{{ route('admin.anggota.reject', $anggotaItem->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menolak pendaftaran ini?')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-sm font-semibold">
                                <i data-lucide="x-circle" class="w-4 h-4"></i> Tolak
                            </button>
                        </form>
                        <form action="{{ route('admin.anggota.destroy', $anggotaItem->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus anggota ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 text-red-800 hover:bg-red-200 text-sm font-semibold">
                                <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Pagination --}}
    <div class="pt-8 flex justify-center">
        {{ $anggota->links() }}
    </div>
</div>

{{-- Inisialisasi ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
