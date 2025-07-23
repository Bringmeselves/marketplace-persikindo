@extends('layouts.app')

@section('title', 'Keranjang Saya')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Keranjang Saya</h2>
                <p class="text-sm text-gray-500">Kelola produk yang ingin Anda beli di sini.</p>
            </div>

            {{-- Tombol Checkout --}}
            @if (count($keranjang))
            <form action="{{ route('user.keranjang.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <input type="hidden" name="keys[]" id="selected-keys">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl font-semibold text-sm transition">
                    Checkout Terpilih
                </button>
            </form>
            @endif
        </div>

        @forelse ($keranjang as $key => $item)
            @php
                $produkItem = $produkList[$item['produk_id']];
                $selectedVarian = $produkItem->varian->where('id', $item['varian_id'])->first();
            @endphp

            <div class="border border-gray-200 rounded-2xl p-6 space-y-6 shadow-sm">
                <div class="flex flex-col md:flex-row gap-6 items-start">

                    {{-- Checkbox --}}
                    <div class="flex-shrink-0 pt-2">
                        <input type="checkbox" class="checkout-checkbox" value="{{ $key }}">
                    </div>

                    {{-- Gambar --}}
                    <div class="w-full md:w-32 h-32 bg-gray-100 rounded-xl overflow-hidden">
                        <img id="gambar-produk-{{ $key }}"
                            src="{{ asset('storage/' . ($selectedVarian->gambar ?? $item['gambar'])) }}"
                            alt="{{ $item['nama_produk'] }}"
                            class="w-full h-full object-cover">
                    </div>

                    {{-- Informasi Produk --}}
                    <div class="flex-1 space-y-1">
                        <h4 class="text-lg font-semibold text-gray-900">
                            {{ $item['nama_produk'] }}
                        </h4>

                        <p class="text-sm text-gray-500">Harga Satuan: <span id="harga-{{ $key }}">Rp{{ number_format($selectedVarian->harga ?? $item['harga_satuan'], 0, ',', '.') }}</span></p>
                        <p class="text-sm text-gray-500" id="stok-{{ $key }}">Stok: {{ $selectedVarian->stok ?? '-' }}</p>

                        <div class="flex justify-between font-semibold text-gray-800 border-t pt-2">
                            <span>Total</span>
                            <span>Rp{{ number_format($item['total_harga'], 0, ',', '.') }}</span>
                        </div>

                        {{-- Form Update --}}
                        <form action="{{ route('user.keranjang.update', $key) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                            @csrf @method('PUT')

                            {{-- Varian --}}
                            <div>
                                <label for="varian-select-{{ $key }}" class="block text-sm font-medium text-gray-700 mb-1">Varian</label>
                                <select name="varian_id" id="varian-select-{{ $key }}"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-500"
                                        data-key="{{ $key }}">
                                    @foreach ($produkItem->varian as $v)
                                        <option value="{{ $v->id }}"
                                            data-harga="{{ $v->harga }}"
                                            data-stok="{{ $v->stok }}"
                                            data-gambar="{{ asset('storage/' . $v->gambar) }}"
                                            @selected($item['varian_id'] == $v->id)>
                                            {{ $v->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jumlah --}}
                            <div>
                                <label for="jumlah-{{ $key }}" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                <input type="number" name="jumlah" id="jumlah-{{ $key }}" value="{{ $item['jumlah'] }}" min="1"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring focus:border-blue-500">
                            </div>

                            {{-- Tombol Update --}}
                            <div class="self-end">
                                <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold text-sm transition">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Aksi: Hapus --}}
                <div class="flex justify-end items-center gap-4 pt-4 border-t mt-4">
                    <form action="{{ route('user.keranjang.destroy', $key) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini dari keranjang?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-semibold text-sm transition">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-6">
                Keranjang Anda kosong.
            </div>
        @endforelse
    </div>
</div>

{{-- Script Icon & Varian --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();

        // Update varian dinamis
        const selects = document.querySelectorAll('select[id^="varian-select-"]');
        selects.forEach(select => {
            select.addEventListener('change', (e) => {
                const selected = e.target.selectedOptions[0];
                const harga = selected.dataset.harga;
                const stok = selected.dataset.stok;
                const gambar = selected.dataset.gambar;
                const key = e.target.dataset.key;

                document.getElementById('harga-' + key).textContent = 'Rp' + Number(harga).toLocaleString('id-ID');
                document.getElementById('stok-' + key).textContent = 'Stok: ' + stok;
                document.getElementById('gambar-produk-' + key).src = gambar;
            });
        });

        // Handle checkout selection
        const checkboxes = document.querySelectorAll('.checkout-checkbox');
        const checkoutForm = document.getElementById('checkoutForm');
        const selectedKeysInput = document.getElementById('selected-keys');

        checkoutForm.addEventListener('submit', function(e) {
            const selected = Array.from(checkboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selected.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu produk untuk checkout.');
                return;
            }

            // Buat input hidden array untuk dikirim
            selectedKeysInput.remove();
            selected.forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'keys[]';
                input.value = key;
                checkoutForm.appendChild(input);
            });
        });
    });
</script>

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
