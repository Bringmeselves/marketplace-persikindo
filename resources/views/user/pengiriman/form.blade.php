@extends('layouts.app')

@section('title', isset($pengiriman) ? 'Ubah Alamat Pengiriman' : 'Tambah Alamat Pengiriman')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800 space-y-10">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ isset($pengiriman) ? 'Ubah Alamat Pengiriman' : 'Tambah Alamat Pengiriman' }}
            </h2>
            <p class="text-sm text-gray-500">Isi informasi alamat tujuan pengiriman produk Anda.</p>
        </div>

        <form action="{{ isset($pengiriman) 
            ? route('user.pengiriman.alamat.update', $checkout->id) 
            : route('user.pengiriman.alamat.store', $checkout->id) }}" 
            method="POST" class="space-y-6">

            @csrf
            @if(isset($pengiriman))
                @method('PUT')
            @endif

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="nama_lengkap">Nama Lengkap</label>
                <input id="nama_lengkap" type="text" name="nama_lengkap"
                    value="{{ old('nama_lengkap', $pengiriman->nama_lengkap ?? '') }}"
                    class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 text-gray-900"
                    required>
                @error('nama_lengkap') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Alamat Penerima --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="alamat_penerima">Alamat Penerima</label>
                <textarea id="alamat_penerima" name="alamat_penerima" rows="4"
                    class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 resize-none text-gray-900"
                    required>{{ old('alamat_penerima', $pengiriman->alamat_penerima ?? '') }}</textarea>
                @error('alamat_penerima') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Kota Tujuan --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="cities">Kota Tujuan</label>
                <select id="cities" name="cities"
                    class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 text-gray-900"
                    required>
                    <option value="">Memuat daftar kota...</option>
                </select>
                @error('cities') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Kode Pos --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="kode_pos">Kode Pos</label>
                <input id="kode_pos" type="text" name="kode_pos"
                    value="{{ old('kode_pos', $pengiriman->kode_pos ?? '') }}"
                    class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 text-gray-900">
                @error('kode_pos') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Nomor WhatsApp --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2" for="nomor_wa">Nomor WhatsApp</label>
                <input id="nomor_wa" type="text" name="nomor_wa"
                    value="{{ old('nomor_wa', $pengiriman->nomor_wa ?? '') }}"
                    class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 text-gray-900">
                @error('nomor_wa') 
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <div class="text-right pt-4">
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-xl shadow transition duration-200">
                    <i data-lucide="save" class="w-5 h-5"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script> lucide.createIcons(); </script>

{{-- Fetch daftar kota dari Komerce --}}
<script>
    const selectedCityId = "{{ old('cities', $pengiriman->cities ?? '') }}";

    fetch('{{ route("user.pengiriman.kota") }}')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('cities');
            select.innerHTML = '<option value="">Pilih Kota Tujuan</option>';

            if (data.status === 'success') {
                data.data.forEach(city => {
                    const isSelected = city.id == selectedCityId ? 'selected' : '';
                    select.innerHTML += `<option value="${city.id}" ${isSelected}>${city.label}</option>`;
                });
            } else {
                alert('Gagal mengambil daftar kota: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal menghubungi server.');
        });
</script>
@endsection
