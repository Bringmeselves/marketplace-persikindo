@extends('layouts.admin')

@section('title', 'Daftar Anggota')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Anggota</h2>
            <p class="text-sm text-gray-500">Kelola pendaftaran dan status anggota Persikindo.</p>
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

        {{-- Tabel Anggota --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Nama</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Terdaftar</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($anggota as $anggotaItem)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $anggotaItem->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                {{ $anggotaItem->user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium capitalize
                                    @if($anggotaItem->status === 'approved') bg-green-100 text-green-700
                                    @elseif($anggotaItem->status === 'rejected') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ $anggotaItem->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ $anggotaItem->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex flex-wrap gap-2">

                                {{-- Lihat --}}
                                <a href="{{ route('admin.anggota.show', $anggotaItem->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow transition">
                                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i> Lihat
                                </a>

                                {{-- Verifikasi --}}
                                <form action="{{ route('admin.anggota.verify', $anggotaItem->id) }}" method="POST">
                                    @csrf
                                    <button type="button"
                                            class="btn-verifikasi inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-green-700 transition"
                                            data-name="{{ $anggotaItem->user->name }}">
                                        <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Verifikasi
                                    </button>
                                </form>

                                {{-- Tolak --}}
                                <form action="{{ route('admin.anggota.reject', $anggotaItem->id) }}" method="POST">
                                    @csrf
                                    <button type="button"
                                            class="btn-tolak inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-yellow-600 transition"
                                            data-name="{{ $anggotaItem->user->name }}">
                                        <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i> Tolak
                                    </button>
                                </form>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.anggota.destroy', $anggotaItem->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn-delete inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 transition"
                                            data-name="{{ $anggotaItem->user->name }}">
                                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-gray-500">
                                <i data-lucide="users" class="w-6 h-6 mx-auto mb-2"></i>
                                Belum ada data anggota.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Navigasi jika pakai pagination --}}
        @if(method_exists($anggota, 'links'))
            <div>{{ $anggota->links('pagination::tailwind') }}</div>
        @endif

    </div>
</div>

{{-- SweetAlert & Lucide --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();

        // Konfirmasi hapus anggota
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const nama = this.dataset.name;

                Swal.fire({
                    title: 'Yakin hapus anggota?',
                    text: `Akun "${nama}" akan dihapus permanen.`,
                    icon: 'warning',
                    iconColor: '#e3342f',
                    background: '#ffffff',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button',
                        cancelButton: 'swal-attractive-button'
                    }
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Konfirmasi verifikasi anggota
        document.querySelectorAll('.btn-verifikasi').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const nama = this.dataset.name;

                Swal.fire({
                    title: 'Verifikasi Anggota?',
                    text: `Anggota "${nama}" akan disetujui sebagai penjual.`,
                    icon: 'question',
                    iconColor: '#38a169',
                    background: '#ffffff',
                    showCancelButton: true,
                    confirmButtonColor: '#38a169',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, verifikasi',
                    cancelButtonText: 'Batal',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button',
                        cancelButton: 'swal-attractive-button'
                    }
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Konfirmasi penolakan anggota dengan alasan
        document.querySelectorAll('.btn-tolak').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                const nama = this.dataset.name;

                Swal.fire({
                    html: `
                        <p class="mb-2 text-sm text-gray-700">
                            Silakan pilih alasan yang relevan untuk menolak pendaftaran 
                            <strong>${nama}</strong>:
                        </p>
                        <select id="alasan" class="swal2-select"
                            style="
                                width: 100%;
                                padding: 0.4rem 0.6rem;
                                font-size: 0.875rem;
                                border-radius: 0.375rem;
                                border: 1px solid #d1d5db;
                                max-width: 100%;
                                box-sizing: border-box;
                            ">
                            <option value="" disabled selected>-- Pilih alasan penolakan --</option>
                            <option value="Bukan pelaku UMKM aktif">Bukan pelaku UMKM aktif</option>
                            <option value="Usaha yang didaftarkan belum jelas atau belum berjalan">Usaha belum jelas/berjalan</option>
                            <option value="Data pendaftaran belum lengkap atau tidak valid">Data belum lengkap/tidak valid</option>
                            <option value="Bukan perempuan (tidak sesuai kriteria keanggotaan)">Bukan perempuan (tidak sesuai)</option>
                            <option value="Domisili di luar wilayah cakupan keanggotaan">Domisili di luar wilayah</option>
                            <option value="Belum siap mengikuti kegiatan atau kontribusi komunitas">Belum siap berkontribusi</option>
                            <option value="Pendaftaran dilakukan ganda atau duplikat">Pendaftaran duplikat</option>
                        </select>
                    `,

                    icon: 'warning',
                    iconColor: '#f97316', // orange-500
                    background: '#ffffff',
                    showCancelButton: true,
                    confirmButtonColor: '#f87171',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Tolak Sekarang',
                    cancelButtonText: 'Batal',
                    width: '400px',
                    padding: '1.75rem',
                    showCloseButton: true,
                    customClass: {
                        popup: 'swal-attractive-popup',
                        title: 'swal-attractive-title',
                        confirmButton: 'swal-attractive-button',
                        cancelButton: 'swal-attractive-button'
                    },
                    preConfirm: () => {
                        const alasan = document.getElementById('alasan').value;
                        if (!alasan) {
                            Swal.showValidationMessage('Silakan pilih alasan penolakan terlebih dahulu.');
                        }
                        return alasan;
                    }
                }).then(result => {
                    if (result.isConfirmed) {
                        const alasanInput = document.createElement('input');
                        alasanInput.type = 'hidden';
                        alasanInput.name = 'catatan';
                        alasanInput.value = result.value;

                        form.appendChild(alasanInput);
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
