@extends('layouts.app')

@section('title', 'Formulir Pendaftaran Anggota')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Formulir Pendaftaran Anggota</h2>
            <p class="text-sm text-gray-500">Isi informasi keanggotaan Anda di bawah ini untuk menjadi anggota resmi Persikindo.</p>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 mt-0.5"></i>
                <div><strong class="block font-semibold">{{ session('success') }}</strong></div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <i data-lucide="x-circle" class="w-5 h-5 mt-0.5"></i>
                <div><strong class="block font-semibold">{{ session('error') }}</strong></div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl text-sm flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
                <div>
                    <strong class="block font-semibold">Terjadi kesalahan:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- Form --}}
        <form id="form-pendaftaran-anggota" action="{{ route('user.anggota.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                           class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900">
                </div>
                <div>
                    <label for="nik" class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" id="nik" value="{{ old('nik') }}" required
                           class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900">
                </div>
                <div>
                    <label for="nama_perusahaan" class="block text-sm font-semibold text-gray-700 mb-1">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" id="nama_perusahaan" value="{{ old('nama_perusahaan') }}" required
                           class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900">
                </div>
                <div>
                    <label for="legalitas" class="block text-sm font-semibold text-gray-700 mb-1">Legalitas</label>
                    <select name="legalitas" id="legalitas" required
                            class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900">
                        <option value="" disabled {{ old('legalitas') ? '' : 'selected' }}>Pilih Legalitas</option>
                        <option value="CV" {{ old('legalitas') == 'CV' ? 'selected' : '' }}>CV</option>
                        <option value="PT" {{ old('legalitas') == 'PT' ? 'selected' : '' }}>PT</option>
                    </select>
                </div>
                <div>
                    <label for="nib" class="block text-sm font-semibold text-gray-700 mb-1">NIB</label>
                    <input type="text" name="nib" id="nib" value="{{ old('nib') }}" required
                           class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900">
                </div>
                <div>
                    <label for="npwp" class="block text-sm font-semibold text-gray-700 mb-1">NPWP</label>
                    <input type="text" name="npwp" id="npwp" value="{{ old('npwp') }}" required
                           class="w-full rounded-xl border border-gray-300 px-5 py-3 shadow-sm focus:ring focus:ring-indigo-200/50 focus:border-indigo-500 text-gray-900">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="bukti_pendaftaran" class="block text-sm font-semibold text-gray-700 mb-1">
                        Bukti Pendaftaran (KTP atau Kartu Anggota)
                    </label>
                    <input type="file" name="bukti_pendaftaran" id="bukti_pendaftaran" required accept=".jpg,.jpeg,.png,.pdf"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Jenis file: JPG, JPEG, PNG, atau PDF</p>
                </div>

                <div>
                    <label for="sertifikat_halal" class="block text-sm font-semibold text-gray-700 mb-1">Sertifikat Halal (opsional)</label>
                    <input type="file" name="sertifikat_halal" id="sertifikat_halal" accept=".jpg,.jpeg,.png,.pdf"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Jenis file: JPG, JPEG, PNG, atau PDF</p>
                </div>

                <div>
                    <label for="pirt" class="block text-sm font-semibold text-gray-700 mb-1">PIRT</label>
                    <input type="file" name="pirt" id="pirt" accept=".jpg,.jpeg,.png,.pdf"
                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">Jenis file: JPG, JPEG, PNG, atau PDF</p>
                </div>
            </div>

            <div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold transition">
                    <i data-lucide="send" class="w-5 h-5"></i> Kirim Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const username = @json(Auth::user()->name);

        Swal.fire({
            title: `👋 Hai, ${username}`,
            html: `
                <p style="margin-bottom: 0.5rem;">Selamat datang di <strong style="color:#3b82f6;">PERSIKINDO</strong>!</p>
                <p style="font-size:14px; color:#4b5563;">
                    Kami senang kamu bergabung. Yuk lengkapi data agar permohonanmu bisa segera kami proses.
                </p>
            `,
            icon: 'info',
            iconColor: '#3b82f6',
            background: '#ffffff',
            showCloseButton: true,
            confirmButtonText: 'Lengkapi Sekarang',
            confirmButtonColor: '#3b82f6',
            width: '360px',
            padding: '1.75rem',
            customClass: {
                popup: 'swal-attractive-popup',
                title: 'swal-attractive-title',
                confirmButton: 'swal-attractive-button'
            }
        });
    });
</script>

<style>
    .swal-attractive-popup {
        border-radius: 1rem;
        box-shadow: 0 15px 30px rgba(0,0,0,0.05);
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
        background-color: #2563eb !important;
    }
</style>
@endsection
