@extends('layouts.app')

@section('title', 'Pilih Kurir')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900 pb-4 border-b">Pilih Jasa Pengiriman</h2>

    <div class="bg-white shadow-lg rounded-2xl p-6 space-y-6">
        {{-- Form pengiriman --}}
        <form action="{{ route('user.pengiriman.kurir.update', $checkout->id) }}" method="POST" class="space-y-6" id="kurirForm">
            @csrf
            @method('PUT')

            {{-- Input hidden untuk menyimpan pilihan kurir, layanan, dan ongkir --}}
            <input type="hidden" name="kurir" id="inputKurir">
            <input type="hidden" name="layanan" id="inputLayanan">
            <input type="hidden" name="ongkir" id="ongkir">

            {{-- Tombol pilihan kurir --}}
            <div>
            <p class="block text-sm font-semibold text-gray-700 mb-3">Pilih Kurir</p>

            <div class="flex flex-col space-y-3">
                {{-- Loop kurir dari server dan tampilkan sebagai tombol pilihan --}}
                @foreach ($kurirList as $kurir)
                    <button
                        type="button"
                        onclick="pilihKurir('{{ $kurir['code'] }}')"
                        id="kurir-btn-{{ $kurir['code'] }}"
                        class="w-full border border-gray-300 bg-white dark:bg-white hover:bg-gray-100 rounded-2xl p-4 flex items-center gap-4 text-left transition">
                        {{-- Ikon Lucide --}}
                        <i data-lucide="truck" class="w-6 h-6 text-indigo-500"></i>

                        {{-- Info nama kurir --}}
                        <div class="flex-1">
                            <p class="text-base font-semibold text-gray-800">{{ $kurir['name'] }}</p>
                            <p class="text-xs text-gray-500">Klik untuk memilih</p>
                        </div>

                        {{-- Ikon panah kanan --}}
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                    </button>
                @endforeach
            </div>
        </div>

            {{-- Container untuk layanan (akan muncul setelah kurir dipilih) --}}
            <div id="layananContainer" class="hidden mt-6">
                <p class="block text-sm font-semibold text-gray-700 mb-3">Pilih Layanan</p>

                <div id="layananButtons" class="flex flex-col space-y-3">
                    {{-- Contoh tombol layanan, nanti diisi lewat JavaScript --}}
                    <button
                        type="button"
                        onclick="pilihLayanan('REG', 10000)"
                        id="layanan-btn-REG"
                        class="w-full border border-gray-300 bg-white dark:bg-white hover:bg-gray-100 rounded-2xl p-4 flex items-center gap-4 text-left transition">
                        {{-- Ikon Lucide --}}
                        <i data-lucide="package" class="w-6 h-6 text-indigo-500"></i>

                        {{-- Info layanan --}}
                        <div class="flex-1">
                            <p class="text-base font-semibold text-gray-800">Regular Service</p>
                            <p class="text-xs text-gray-500">Estimasi 2-3 hari</p>
                        </div>

                        {{-- Ikon panah kanan --}}
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                    </button>
                </div>
            </div>

            {{-- Tampilkan ongkir setelah layanan dipilih --}}
            <div id="ongkir-display" class="hidden mt-6">
                <p class="block text-sm font-semibold text-gray-700 mb-3">Biaya Ongkir</p>

                <div class="flex flex-col space-y-3">
                    <button
                        type="button"
                        disabled
                        class="w-full border border-gray-300 bg-white dark:bg-white hover:bg-gray-100 rounded-2xl p-4 flex items-center gap-4 text-left cursor-default transition">
                        
                        {{-- Ikon dompet --}}
                        <i data-lucide="wallet" class="w-6 h-6 text-indigo-500"></i>

                        {{-- Info ongkir --}}
                        <div class="flex-1">
                            <p class="text-base font-semibold text-gray-800">Ongkir</p>
                            <p id="ongkir-text" class="text-sm text-gray-500">Rp 0</p>
                        </div>

                        {{-- Ikon panah kanan transparan agar rata --}}
                        <i data-lucide="chevron-right" class="w-4 h-4 text-transparent"></i>
                    </button>
                </div>
            </div>

            {{-- Tombol simpan untuk mengirim form --}}
            <div class="text-right">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                    <i data-lucide="truck" class="w-5 h-5"></i> Simpan Kurir
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Script icon Lucide --}}
<script src="https://unpkg.com/lucide@latest"></script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Inisialisasi icon lucide
        lucide.createIcons();

        // Element referensi dari DOM
        const layananContainer = document.getElementById('layananContainer');
        const layananButtons = document.getElementById('layananButtons');
        const ongkirInput = document.getElementById('ongkir');
        const ongkirText = document.getElementById('ongkir-text');
        const ongkirDisplay = document.getElementById('ongkir-display');
        const inputKurir = document.getElementById('inputKurir');
        const inputLayanan = document.getElementById('inputLayanan');

        // Data dari server via blade
        const origin = @json($origin);
        const destination = @json($destination);
        const berat = @json($weight);
        const checkoutId = @json($checkout->id);

        // Fungsi saat user memilih kurir
        window.pilihKurir = async function (kurir) {
            inputKurir.value = kurir; // Set nilai input hidden

            // Reset UI
            layananButtons.innerHTML = `<span class="text-gray-500">Memuat layanan...</span>`;
            layananContainer.classList.remove('hidden');
            ongkirInput.value = '';
            ongkirText.textContent = '';
            ongkirDisplay.classList.add('hidden');

            try {
                // Fetch layanan ongkir berdasarkan kurir yang dipilih
                const res = await fetch(`{{ route('user.pengiriman.cekOngkir') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        cities: destination,
                        berat: berat,
                        kurir: kurir,
                        checkout_id: checkoutId
                    })
                });

                const json = await res.json();
                console.log('✅ Response dari cekOngkir:', json);

                if (json.status === 'success' && json.data.length > 0) {
                    layananButtons.innerHTML = ''; // Bersihkan layanan lama

                    // Tampilkan tombol layanan
                    json.data.forEach(option => {
                        const cost = option.shipping_cost ?? option.tariff ?? option.price ?? 0;

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'bg-gray-100 hover:bg-indigo-100 text-gray-700 px-4 py-2 rounded-xl shadow';
                        btn.textContent = `${option.service_name} - Rp${parseInt(cost).toLocaleString()} (${option.etd} hari)`;
                        btn.onclick = () => pilihLayanan(option.service_name, cost);
                        layananButtons.appendChild(btn);
                    });
                } else {
                    layananButtons.innerHTML = `<span class="text-red-500">Tidak ada layanan ditemukan</span>`;
                }
            } catch (err) {
                console.error('❌ Error saat fetch ongkir:', err);
                layananButtons.innerHTML = `<span class="text-red-500">Gagal memuat layanan</span>`;
            }
        };

        // Fungsi saat user memilih layanan
        window.pilihLayanan = function (layanan, ongkir) {
            inputLayanan.value = layanan; // Set input hidden layanan
            ongkirInput.value = ongkir; // Set input hidden ongkir

            // Tampilkan ongkir ke user
            ongkirText.textContent = `Rp${parseInt(ongkir).toLocaleString()}`;
            ongkirDisplay.classList.remove('hidden');

            // Jika ingin langsung submit form, aktifkan baris di bawah:
            // document.getElementById('kurirForm').submit();
        };
    });
</script>
@endpush
@endsection
