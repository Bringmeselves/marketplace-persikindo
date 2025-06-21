 
@extends('layouts.app')

@section('title', 'Edit Item Checkout')

@section('content')
<div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">

    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
        <i data-lucide="pencil" class="w-5 h-5 text-yellow-500"></i>
        Edit Item Checkout
    </h2>

    <form action="{{ route('user.checkout.item.update', [$checkout->id, $item->id]) }}" method="POST" class="bg-white shadow rounded-2xl p-6 space-y-6">
        @csrf
        @method('PUT')

        {{-- Pilih Varian --}}
        <div>
            <label for="varian_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Varian</label>
            <select name="varian_id" id="varian_id" required class="w-full rounded-md border-gray-300 shadow-sm">
                @foreach ($checkout->toko->produk as $produk)
                    <optgroup label="{{ $produk->nama }}">
                        @foreach ($produk->varian as $varian)
                            <option value="{{ $varian->id }}"
                                data-produk-id="{{ $produk->id }}"
                                {{ $varian->id == $item->varian_id ? 'selected' : '' }}>
                                {{ $varian->nama }} - Rp{{ number_format($varian->harga ?? $produk->harga, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        {{-- Jumlah --}}
        <div>
            <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" value="{{ $item->jumlah }}" min="1" required class="w-full rounded-md border-gray-300 shadow-sm">
        </div>

        {{-- Tombol Simpan --}}
        <div class="text-right">
            <button type="submit"
                class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-xl shadow transition">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
        </div>
    </form>

    {{-- Tombol Kembali --}}
    <div class="text-center">
        <a href="{{ route('user.checkout.create', $checkout->id) }}"
           class="inline-block text-sm text-indigo-600 hover:underline">
            ← Kembali ke Halaman Checkout
        </a>
    </div>
</div>

{{-- Lucide --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>
@endsection
