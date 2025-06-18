@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900 mb-8 flex items-center gap-2">
        <i data-lucide="truck" class="w-6 h-6 text-indigo-600"></i>
        Pilih Jasa Pengiriman
    </h2>

    <form action="{{ route('user.pengiriman.kurir.update', $pengiriman->checkout_id) }}" method="POST"
          class="bg-white shadow-lg rounded-2xl p-6 md:p-8 space-y-6 border border-gray-200">
        @csrf

        <div>
            <label for="kurir" class="block text-sm font-semibold text-gray-700 mb-2">Kurir</label>
            <select id="kurir" name="kurir" required
                    class="w-full border border-gray-300 rounded-lg shadow-sm text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Pilih Kurir</option>
                <option value="jne" {{ old('kurir', $pengiriman->kurir) == 'jne' ? 'selected' : '' }}>JNE</option>
                <option value="jnt" {{ old('kurir', $pengiriman->kurir) == 'jnt' ? 'selected' : '' }}>J&T</option>
                <option value="pos" {{ old('kurir', $pengiriman->kurir) == 'pos' ? 'selected' : '' }}>POS Indonesia</option>
                <option value="sicepat" {{ old('kurir', $pengiriman->kurir) == 'sicepat' ? 'selected' : '' }}>SiCepat</option>
            </select>
        </div>

        <div>
            <label for="layanan" class="block text-sm font-semibold text-gray-700 mb-2">Layanan</label>
            <input type="text" id="layanan" name="layanan" required
                   value="{{ old('layanan', $pengiriman->layanan ?? '') }}"
                   placeholder="Contoh: Reguler, Same Day"
                   class="w-full border border-gray-300 rounded-lg shadow-sm text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        <div>
            <label for="ongkir" class="block text-sm font-semibold text-gray-700 mb-2">Ongkir (Rp)</label>
            <input type="number" id="ongkir" name="ongkir" required
                   value="{{ old('ongkir', $pengiriman->ongkir ?? '') }}"
                   placeholder="Masukkan biaya ongkir"
                   class="w-full border border-gray-300 rounded-lg shadow-sm text-sm p-3 focus:ring-indigo-500 focus:border-indigo-500" />
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-right pt-6">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-7 rounded-xl shadow transition duration-200">
                <i data-lucide="save" class="w-5 h-5"></i> Simpan
            </button>
        </div>
    </form>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
