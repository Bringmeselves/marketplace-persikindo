@extends('layouts.app')

@section('title', 'Pilih Kurir')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-2xl shadow-md space-y-6">
    <h2 class="text-xl font-semibold">Pilih Kurir</h2>

    <div class="space-y-2 text-sm">
        <p><strong>Produk:</strong> {{ $checkout->produk->nama_produk }}</p>
        <p><strong>Asal:</strong> {{ $origin }}</p>
        <p><strong>Tujuan:</strong> {{ $destination }}</p>
        <p><strong>Berat:</strong> {{ $weight }} gram</p>
    </div>

    <form action="{{ route('user.pengiriman.kurir.update', $checkout->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium">Kurir</label>
            <select id="kurir" name="kurir" class="w-full border rounded-xl px-3 py-2">
                <option value="">Pilih Kurir</option>
                @foreach ($kurirList as $kurir)
                    <option value="{{ $kurir['code'] }}">{{ $kurir['name'] }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium">Layanan</label>
            <select id="layanan" name="layanan" class="w-full border rounded-xl px-3 py-2" disabled>
                <option value="">-- Pilih Layanan --</option>
            </select>
        </div>

        <input type="hidden" name="ongkir" id="ongkir">

        <div id="ongkir-display" class="text-blue-600 font-semibold hidden">
            Shipping Cost: <span id="ongkir-text"></span>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl">Simpan Kurir</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const kurirSelect = document.getElementById('kurir');
        const layananSelect = document.getElementById('layanan');
        const ongkirInput = document.getElementById('ongkir');
        const ongkirText = document.getElementById('ongkir-text');
        const ongkirDisplay = document.getElementById('ongkir-display');

        const origin = @json($origin);
        const destination = @json($destination);
        const berat = @json($weight);
        const checkoutId = @json($checkout->id);

        kurirSelect.addEventListener('change', async () => {
            const kurir = kurirSelect.value;
            layananSelect.innerHTML = `<option value="">-- Memuat layanan... --</option>`;
            layananSelect.disabled = true;
            ongkirInput.value = '';
            ongkirText.textContent = '';
            ongkirDisplay.classList.add('hidden');

            if (!kurir) return;

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
                console.log('✅ Response dari cekOngkir:', json);

                if (json.status === 'success' && json.data.length > 0) {
                    layananSelect.innerHTML = `<option value="">-- Pilih Layanan --</option>`;
                    json.data.forEach(option => {
                        const cost = option.shipping_cost ?? option.tariff ?? option.price ?? 0;

                        const opt = document.createElement('option');
                        opt.value = option.service_name;
                        opt.textContent = `${option.service_name} - Rp${parseInt(cost).toLocaleString()} (${option.etd} hari)`;
                        opt.dataset.tariff = cost;
                        layananSelect.appendChild(opt);
                    });
                    layananSelect.disabled = false;
                } else {
                    layananSelect.innerHTML = `<option value="">Tidak ada layanan ditemukan</option>`;
                    layananSelect.disabled = true;
                }
            } catch (err) {
                console.error('❌ Error saat fetch ongkir:', err);
                layananSelect.innerHTML = `<option value="">Gagal memuat layanan</option>`;
                layananSelect.disabled = true;
            }
        });

        layananSelect.addEventListener('change', () => {
            const selected = layananSelect.selectedOptions[0];
            const tariff = selected?.dataset?.tariff;

            if (tariff) {
                ongkirInput.value = tariff;
                ongkirText.textContent = `Rp${parseInt(tariff).toLocaleString()}`;
                ongkirDisplay.classList.remove('hidden');
            } else {
                ongkirInput.value = '';
                ongkirText.textContent = '';
                ongkirDisplay.classList.add('hidden');
            }
        });
    });
</script>
@endpush
