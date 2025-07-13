@extends('layouts.admin')

@section('title', 'Detail Penarikan')

@section('content')
<div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-md border p-6 space-y-10">

        {{-- Judul --}}
        <div class="border-b pb-4">
            <h1 class="text-2xl font-bold flex items-center gap-2 text-gray-900">
                <i data-lucide="wallet" class="w-6 h-6 text-indigo-600"></i>
                Detail Penarikan Dana
            </h1>
            <p class="text-sm text-gray-500">Berikut adalah rincian penarikan dana oleh pengguna.</p>
        </div>

        {{-- Informasi Penarikan --}}
        <div class="space-y-4 text-sm text-gray-700">
            <div class="flex justify-between">
                <span class="font-medium flex items-center gap-2">
                    <i data-lucide="user" class="w-4 h-4 text-gray-500"></i>
                    Nama Pengguna
                </span>
                <span>{{ $penarikan->user->name ?? '-' }}</span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium flex items-center gap-2">
                    <i data-lucide="dollar-sign" class="w-4 h-4 text-gray-500"></i>
                    Jumlah Penarikan
                </span>
                <span class="font-semibold text-gray-900">
                    Rp{{ number_format($penarikan->jumlah, 0, ',', '.') }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium flex items-center gap-2">
                    <i data-lucide="clock" class="w-4 h-4 text-gray-500"></i>
                    Status
                </span>
                <span class="capitalize px-3 py-1 rounded-full text-sm font-medium
                    {{ $penarikan->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-700' }}">
                    {{ $penarikan->status }}
                </span>
            </div>

            <div class="flex justify-between">
                <span class="font-medium flex items-center gap-2">
                    <i data-lucide="calendar" class="w-4 h-4 text-gray-500"></i>
                    Diajukan Pada
                </span>
                <span>{{ $penarikan->created_at->format('d M Y, H:i') }}</span>
            </div>
        </div>

        {{-- Bukti Transfer --}}
        @if($penarikan->bukti_transfer)
            <div class="space-y-2">
                <h2 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                    <i data-lucide="image" class="w-5 h-5 text-gray-700"></i>
                    Bukti Transfer
                </h2>
                <img src="{{ asset('storage/' . $penarikan->bukti_transfer) }}"
                     alt="Bukti Transfer"
                     class="w-full max-w-md rounded shadow border">
            </div>
        @else
            <div class="space-y-4">
                <h2 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                    <i data-lucide="upload" class="w-5 h-5 text-gray-700"></i>
                    Upload Bukti Transfer
                </h2>
                <form action="{{ route('admin.penarikan.update', $penarikan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="bukti_transfer" class="block text-sm font-medium text-gray-700">Pilih file</label>
                        <input type="file" name="bukti_transfer" id="bukti_transfer" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('bukti_transfer')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        Upload & Tandai Selesai
                    </button>
                </form>
            </div>
        @endif

        {{-- Penolakan Penarikan (jika masih pending dan belum disetujui) --}}
        @if($penarikan->status === 'pending' && !$penarikan->bukti_transfer)
            <div class="space-y-4">
                <h2 class="text-lg font-semibold flex items-center gap-2 text-gray-800">
                    <i data-lucide="x-circle" class="w-5 h-5 text-gray-700"></i>
                    Tolak Penarikan
                </h2>
                <form action="{{ route('admin.penarikan.reject', $penarikan->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="catatan" class="block text-sm font-medium text-gray-700">Alasan Penolakan (opsional)</label>
                        <textarea name="catatan" id="catatan" rows="3"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm"
                            placeholder="Masukkan alasan penolakan (jika ada)">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Tolak Penarikan
                    </button>
                </form>
            </div>
        @endif

        {{-- Tombol Kembali --}}
        <div>
            <a href="{{ route('admin.penarikan.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Kembali ke Daftar Penarikan
            </a>
        </div>
    </div>

    {{--ikon lucide --}}
    <script type="module">
        import lucide from 'https://unpkg.com/lucide@latest/dist/esm/lucide.js';
        lucide.createIcons();
    </script>
</div>
@endsection
