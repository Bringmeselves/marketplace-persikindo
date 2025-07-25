@extends('layouts.app')

@section('title', 'Ajukan Penarikan Saldo')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">

    {{-- Kartu Utama --}}
    <div class="bg-gradient-to-br from-white to-blue-50 rounded-2xl shadow-xl p-6 space-y-8 border border-gray-100">

        {{-- Header --}}
        <div class="flex justify-between items-center border-b pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Ajukan Penarikan Saldo</h2>
                <p class="text-sm text-gray-500">Isi formulir berikut untuk menarik saldo ke rekening Anda.</p>
            </div>
            <span class="text-sm px-3 py-1 rounded-full bg-yellow-200 text-yellow-800 font-semibold shadow-sm ring-1 ring-yellow-300">
                <i data-lucide="clock" class="w-4 h-4 inline-block mr-1 -mt-1"></i> Menunggu Verifikasi
            </span>
        </div>

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
                <div>
                    <strong class="block font-semibold">Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        {{-- Form --}}
        <form action="{{ route('user.penarikan.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid sm:grid-cols-2 gap-6 text-sm">
                {{-- Jumlah Penarikan --}}
                <div>
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                        <i data-lucide="banknote" class="w-4 h-4 text-gray-400"></i> Jumlah Penarikan
                    </label>
                    <input type="number" name="jumlah" id="jumlah" min="10000" required
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:bg-blue-50 px-4 py-2 text-gray-800 transition duration-200">
                    <p class="text-xs text-gray-500 mt-1">Minimal penarikan Rp50.000</p>
                </div>

                {{-- Rekening Tujuan --}}
                <div>
                    <label for="rekening_tujuan" class="block text-sm font-medium text-gray-700 mb-1 flex items-center gap-1">
                        <i data-lucide="building-bank" class="w-4 h-4 text-gray-400"></i> Rekening Tujuan
                    </label>
                    <input type="text" name="rekening_tujuan" id="rekening_tujuan" required
                        class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 focus:bg-blue-50 px-4 py-2 text-gray-800 transition duration-200">
                    <p class="text-xs text-gray-500 mt-1">Contoh: BCA 1234567890 a.n. Nama Anda</p>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="pt-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 px-5 rounded-xl shadow-lg transition duration-200">
                    <i data-lucide="wallet-cards" class="w-5 h-5"></i> Ajukan Penarikan
                </button>
                <a href="{{ route('user.penarikan.index') }}"
                    class="text-sm text-gray-600 hover:text-blue-600 hover:underline flex items-center gap-1 transition duration-200">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Lucide --}}
@push('scripts')
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endpush
{{-- SweetAlert Notification --}}
@if (session('success') || session('error') || session('welcome') || session('catatan_penolakan'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    html: `<p style="margin: 0;">{{ session('success') }}</p>`,
                    iconColor: '#10b981', // green-500
                    background: '#ffffff',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#10b981',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    html: `<p style="margin: 0;">{{ session('error') }}</p>`,
                    iconColor: '#ef4444', // red-500
                    background: '#ffffff',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#ef4444',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('welcome'))
                Swal.fire({
                    icon: 'info',
                    title: 'Selamat Datang',
                    html: `<p style="margin: 0;">{{ session('welcome') }}</p>`,
                    iconColor: '#3b82f6', // blue-500
                    background: '#ffffff',
                    confirmButtonText: 'Terima Kasih',
                    confirmButtonColor: '#3b82f6',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif

            @if (session('catatan_penolakan'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Pengajuan Ditolak',
                    html: `{!! nl2br(e(session('catatan_penolakan'))) !!}`,
                    iconColor: '#f59e0b', // amber-500
                    background: '#ffffff',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#f59e0b',
                    width: '360px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button'
                    }
                });
            @endif
        });
    </script>

    <style>
        .swal-attractive-popup {
            border-radius: 1rem;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
            font-family: 'Segoe UI', sans-serif;
        }

        .swal-attractive-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .swal-attractive-button {
            font-size: 14px !important;
            font-weight: 600;
            padding: 10px 20px !important;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .swal-attractive-button:hover {
            filter: brightness(0.95);
        }
    </style>
@endif
@endsection
