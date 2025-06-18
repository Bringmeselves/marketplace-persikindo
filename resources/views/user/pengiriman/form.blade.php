@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-6 sm:px-8 lg:px-10 space-y-8 text-gray-800">

    <h2 class="text-3xl font-bold text-gray-900">
        {{ isset($pengiriman) ? 'Ubah Alamat Pengiriman' : 'Tambah Alamat Pengiriman' }}
    </h2>

    <form action="{{ isset($pengiriman) 
        ? route('user.pengiriman.alamat.update', $checkout->id) 
        : route('user.pengiriman.alamat.store', $checkout->id) }}" 
        method="POST" class="bg-white shadow-lg rounded-2xl p-8 space-y-6 border border-gray-100">

        @csrf
        @if(isset($pengiriman))
            @method('PUT')
        @endif

        {{-- Nama Lengkap --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="nama_lengkap">Nama Lengkap</label>
            <input id="nama_lengkap" type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pengiriman->nama_lengkap ?? '') }}"
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

        {{-- Kota --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="cities">Kota</label>
            <input id="cities" type="text" name="cities" value="{{ old('cities', $pengiriman->cities ?? '') }}"
                class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 text-gray-900">
            @error('cities') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Kode Pos --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="kode_pos">Kode Pos</label>
            <input id="kode_pos" type="text" name="kode_pos" value="{{ old('kode_pos', $pengiriman->kode_pos ?? '') }}"
                class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 text-gray-900">
            @error('kode_pos') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Nomor WhatsApp --}}
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2" for="nomor_wa">Nomor WhatsApp</label>
            <input id="nomor_wa" type="text" name="nomor_wa" value="{{ old('nomor_wa', $pengiriman->nomor_wa ?? '') }}"
                class="w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200/50 shadow-sm px-5 py-3 text-gray-900">
            @error('nomor_wa') 
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p> 
            @enderror
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
