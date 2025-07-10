@extends('layouts.app')

@section('title', 'Pilih Kurir')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Pilih Jasa Pengiriman</h2>
            <p class="text-sm text-gray-500">Silakan pilih kurir dan layanan pengiriman untuk pesanan Anda.</p>
        </div>

        {{-- Form pengiriman --}}
        <form action="{{ route('user.pengiriman.kurir.update', $checkout->id) }}" method="POST" class="space-y-6" id="kurirForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="kurir" id="inputKurir">
            <input type="hidden" name="layanan" id="inputLayanan">
            <input type="hidden" name="ongkir" id="ongkir">

            {{-- Pilih Kurir --}}
            <div>
                <p class="text-sm font-semibold text-gray-700 mb-3">Pilih Kurir</p>
                <div class="space-y-3">
                    @foreach ($kurirList as $kurir)
                        <button
                            type="button"
                            onclick="pilihKurir('{{ $kurir['code'] }}')"
                            id="kurir-btn-{{ $kurir['code'] }}"
                            class="w-full flex items-center justify-between border border-gray-200 bg-white hover:bg-gray-50 rounded-xl px-5 py-4 shadow-sm transition">
                            <div class="flex items-center gap-4">
                                <i data-lucide="truck" class="w-6 h-6 text-indigo-500"></i>
                                <div>
                                    <p class="text-base font-semibold text-gray-800">{{ $kurir['name'] }}</p>
                                    <p class="text-xs text-gray-500">Klik untuk memilih</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Layanan --}}
            <div id="layananContainer" class="hidden">
                <p class="text-sm font-semibold text-gray-700 mt-6 mb-3">Pilih Layanan</p>
                <div id="layananButtons" class="space-y-3"></div>
            </div>

            {{-- Ongkir --}}
            <div id="ongkir-display" class="hidden">
                <p class="text-sm font-semibold text-gray-700 mt-6 mb-3">Biaya Ongkir</p>
                <div class="border border-gray-200 bg-white rounded-xl px-5 py-4 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-4">
                        <i data-lucide="wallet" class="w-6 h-6 text-indigo-500"></i>
                        <div>
                            <p class="text-base font-semibold text-gray-800">Ongkir</p>
                            <p id="ongkir-text" class="text-sm text-gray-500">Rp 0</p>
                        </div>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-transparent"></i>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="pt-6 text-right">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                    <i data-lucide="truck" class="w-5 h-5"></i> Simpan Kurir
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();

        const layananContainer = document.getElementById('layananContainer');
        const layananButtons = document.getElementById('layananButtons');
        const ongkirInput = document.getElementById('ongkir');
        const ongkirText = document.getElementById('ongkir-text');
        const ongkirDisplay = document.getElementById('ongkir-display');
        const inputKurir = document.getElementById('inputKurir');
        const inputLayanan = document.getElementById('inputLayanan');

        const origin = @json($origin);
        const destination = @json($destination);
        const berat = @json($weight);
        const checkoutId = @json($checkout->id);

        window.pilihKurir = async function (kurir) {
            inputKurir.value = kurir;
            layananButtons.innerHTML = `<span class="text-gray-500">Memuat layanan...</span>`;
            layananContainer.classList.remove('hidden');
            ongkirInput.value = '';
            ongkirText.textContent = '';
            ongkirDisplay.classList.add('hidden');

            try {
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
                layananButtons.innerHTML = '';

                if (json.status === 'success' && json.data.length > 0) {
                    json.data.forEach(option => {
                        const cost = option.shipping_cost ?? option.tariff ?? option.price ?? 0;

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'w-full border border-gray-200 bg-white hover:bg-gray-50 rounded-xl px-5 py-4 flex justify-between items-center shadow-sm transition';
                        btn.onclick = () => pilihLayanan(option.service_name, cost);
                        btn.innerHTML = `
                            <div class="flex items-center gap-4">
                                <i data-lucide="package" class="w-6 h-6 text-indigo-500"></i>
                                <div>
                                    <p class="text-base font-semibold text-gray-800">${option.service_name}</p>
                                    <p class="text-xs text-gray-500">${option.etd} hari - Rp${parseInt(cost).toLocaleString()}</p>
                                </div>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400"></i>
                        `;
                        layananButtons.appendChild(btn);
                    });
                    lucide.createIcons();
                } else {
                    layananButtons.innerHTML = `<span class="text-red-500">Tidak ada layanan ditemukan</span>`;
                }
            } catch (err) {
                console.error(err);
                layananButtons.innerHTML = `<span class="text-red-500">Gagal memuat layanan</span>`;
            }
        };

        window.pilihLayanan = function (layanan, ongkir) {
            inputLayanan.value = layanan;
            ongkirInput.value = ongkir;
            ongkirText.textContent = `Rp${parseInt(ongkir).toLocaleString()}`;
            ongkirDisplay.classList.remove('hidden');
        };
    });
</script>
@endpush
@endsection
