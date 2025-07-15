@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 text-gray-800">
    <div class="bg-white rounded-2xl shadow-xl p-6 space-y-10 border border-gray-100">

        {{-- Header --}}
        <div class="border-b pb-4">
            <h2 class="text-2xl font-bold text-gray-900">Keranjang Belanja</h2>
            <p class="text-sm text-gray-500">Lihat dan atur barang-barang yang ingin kamu beli.</p>
        </div>

        {{-- Flash Message --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-700 px-4 py-3 rounded-xl">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">{{ session('success') }}</div>
        @endif

        {{-- Jika Kosong --}}
        @if(count($keranjang) === 0)
            <div class="text-center text-gray-500 py-6">
                Keranjang kamu masih kosong.
            </div>
        @else

        {{-- Daftar Produk --}}
        <div class="space-y-10">
            @foreach($keranjang as $key => $item)
                @php $produkItem = $produk[$item['produk_id']] ?? null; @endphp
                @if($produkItem)
                <div class="border border-gray-200 rounded-2xl p-6 space-y-6 shadow-sm bg-white">

                    {{-- Baris Atas: Nama Produk + Tombol Hapus --}}
                    <div class="flex justify-between items-start">
                        <h4 class="text-lg font-semibold text-gray-900">{{ $item['nama_produk'] }}</h4>

                        <form action="{{ route('user.keranjang.destroy', $key) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="p-0 m-0 bg-transparent border-none text-black hover:text-gray-600 transition"
                                title="Hapus item dari keranjang">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>

                    <div class="flex flex-col md:flex-row gap-6">
                        {{-- Gambar --}}
                        <div class="w-full md:w-32 h-32 bg-gray-100 rounded-xl overflow-hidden">
                            <img src="{{ asset('storage/' . $item['gambar']) }}"
                                alt="{{ $item['nama_produk'] }}"
                                class="w-full h-full object-cover">
                        </div>

                        {{-- Informasi Produk --}}
                        <div class="flex-1 space-y-1">
                            <p class="text-sm text-gray-500">Harga Satuan: Rp{{ number_format($item['harga_satuan'], 0, ',', '.') }}</p>
                            <div class="flex justify-between text-sm text-gray-600">
                                <span>Jumlah</span><span>{{ $item['jumlah'] }}</span>
                            </div>
                            <div class="flex justify-between font-semibold text-gray-800 border-t pt-2">
                                <span>Total</span><span>Rp{{ number_format($item['total_harga'], 0, ',', '.') }}</span>
                            </div>

                            {{-- Form Update --}}
                            <form action="{{ route('user.keranjang.update', $key) }}" method="POST" class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                                @csrf @method('PUT')

                                {{-- Varian --}}
                                <div>
                                    <label for="varian-{{ $key }}" class="block text-sm font-medium text-gray-700 mb-1">Varian</label>
                                    <select name="varian_id" id="varian-{{ $key }}" class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-500 text-sm">
                                        <option value="">Pilih Varian</option>
                                        @foreach($produkItem->varian as $v)
                                            <option value="{{ $v->id }}" @if($item['varian_id'] == $v->id) selected @endif>{{ $v->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Jumlah --}}
                                <div>
                                    <label for="jumlah-{{ $key }}" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                                    <input type="number" name="jumlah" id="jumlah-{{ $key }}" value="{{ $item['jumlah'] }}" min="1"
                                        class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-500 text-sm">
                                </div>

                                {{-- Tombol Update --}}
                                <div>
                                    <button type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl font-semibold text-sm transition">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Tombol Checkout --}}
                        <div class="flex items-end">
                            <form action="{{ route('user.keranjang.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl font-semibold transition text-sm w-full">
                                    Checkout
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
                @endif
            @endforeach
        </div>
        @endif

    </div>
</div>

{{-- Ikon --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
