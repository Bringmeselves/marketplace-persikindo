@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-10 text-gray-800">
    <h2 class="text-3xl font-bold text-gray-900">Checkout</h2>

    {{-- Notifikasi --}}
    @if(session('success') || session('info'))
        <div class="space-y-2">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-green-500 bg-green-50 rounded shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                    <span class="text-sm text-green-800 font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('info'))
                <div class="flex items-center gap-3 p-4 border-l-4 border-blue-500 bg-blue-50 rounded shadow-sm">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600"></i>
                    <span class="text-sm text-blue-800 font-medium">{{ session('info') }}</span>
                </div>
            @endif
        </div>
    @endif

    {{-- Tambah Produk --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 space-y-4">
        <h3 class="text-xl font-semibold text-gray-900 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-5 h-5 text-indigo-500"></i>
            Tambah Produk
        </h3>

        <form action="{{ route('user.checkout.start') }}" method="POST" class="grid md:grid-cols-3 gap-4">
            @csrf
            <input type="hidden" name="produk_id" id="input-produk-id">

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Varian</label>
                <select name="varian_id" id="select-varian" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200">
                    @foreach ($checkout->toko->produk as $produk)
                        <optgroup label="{{ $produk->nama }}">
                            @foreach ($produk->varian as $varian)
                                <option value="{{ $varian->id }}" data-produk-id="{{ $produk->id }}">
                                    {{ $varian->nama }} - Rp{{ number_format($varian->harga ?? $produk->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                <input type="number" name="jumlah" value="1" min="1" required class="w-full rounded-lg border-gray-300 shadow-sm focus:ring focus:ring-indigo-200">
            </div>

            <div class="md:col-span-3 text-right mt-2">
                <button type="submit" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg shadow-sm transition">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah ke Checkout
                </button>
            </div>
        </form>
    </div>

    {{-- Produk Checkout --}}
    @if($checkout->item && count($checkout->item))
        <div class="space-y-6">
            @foreach ($checkout->item as $item)
                <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-40 h-40 flex-shrink-0 rounded-xl overflow-hidden bg-gray-100">
                        <img src="{{ asset('storage/' . $item->gambar) }}"
                             alt="Gambar Produk"
                             class="w-full h-full object-cover">
                    </div>

                    <div class="flex flex-col justify-between flex-grow space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                                @if($item->varian)
                                    <span class="text-sm text-gray-500">({{ $item->varian->nama }})</span>
                                @endif
                            </h3>

                            <ul class="mt-2 space-y-1 text-sm text-gray-600">
                                <li class="flex justify-between">
                                    <span>Jumlah</span>
                                    <span class="font-medium text-gray-800">{{ $item->jumlah }}</span>
                                </li>
                                <li class="flex justify-between font-bold text-gray-900 border-t pt-2">
                                    <span>Subtotal</span>
                                    <span>Rp{{ number_format($item->total_harga, 0, ',', '.') }}</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex flex-col md:flex-row justify-end gap-2">
                            <a href="{{ route('user.checkout.item.edit', [$checkout->id, $item->id]) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-100 text-yellow-800 hover:bg-yellow-200 text-sm font-semibold">
                                <i data-lucide="pencil" class="w-4 h-4"></i>Edit
                            </a>
                            <form action="{{ route('user.checkout.item.destroy', [$checkout->id, $item->id]) }}" method="POST" onsubmit="return confirm('Hapus item ini?')">
                                @csrf @method('DELETE')
                                <button class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 text-sm font-semibold">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 flex justify-between items-center text-lg font-bold text-gray-900">
            <span>Total Harga</span>
            <span class="text-indigo-600">Rp{{ number_format($checkout->total_harga, 0, ',', '.') }}</span>
        </div>
    @endif

    {{-- Info Toko --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8 flex items-center gap-6">
        <img src="{{ asset('storage/' . $checkout->toko->foto_toko) }}" class="w-16 h-16 rounded-full object-cover border" alt="Foto Toko">
        <div>
            <h4 class="font-semibold text-gray-900">{{ $checkout->toko->nama_toko }}</h4>
            <p class="text-sm text-gray-600">{{ $checkout->toko->alamat }}</p>
        </div>
    </div>

    {{-- Alamat Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="flex items-center gap-2 text-xl font-semibold text-gray-900">
                <i data-lucide="map-pin" class="w-5 h-5 text-indigo-500"></i> Alamat Pengiriman
            </h3>
            <a href="{{ $checkout->pengiriman ? route('user.pengiriman.alamat.edit', $checkout->id) : route('user.pengiriman.alamat.create', $checkout->id) }}"
               class="text-sm text-indigo-600 hover:underline">
                {{ $checkout->pengiriman ? 'Ubah Alamat' : 'Tambah Alamat' }}
            </a>
        </div>
        @if ($checkout->pengiriman)
            <div class="space-y-1 text-gray-700 text-sm">
                <p class="font-semibold">{{ $checkout->pengiriman->nama_lengkap }}</p>
                <p>{{ $checkout->pengiriman->alamat_penerima }}</p>
                <p>{{ $checkout->pengiriman->city_name }}, {{ $checkout->pengiriman->kode_pos }}</p>
                <p>WA: {{ $checkout->pengiriman->nomor_wa }}</p>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Belum ada alamat pengiriman.</p>
        @endif
    </div>

    {{-- Jasa Pengiriman --}}
    <div class="bg-white shadow-lg rounded-2xl p-6 md:p-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="flex items-center gap-2 text-xl font-semibold text-gray-900">
                <i data-lucide="truck" class="w-5 h-5 text-indigo-500"></i> Jasa Pengiriman
            </h3>
            @if ($checkout->pengiriman)
                <a href="{{ route('user.pengiriman.kurir.edit', $checkout->id) }}" class="text-sm text-indigo-600 hover:underline">
                    {{ $checkout->pengiriman->kurir ? 'Ubah Kurir' : 'Pilih Kurir' }}
                </a>
            @endif
        </div>
        @if ($checkout->pengiriman && $checkout->pengiriman->kurir)
            <div class="space-y-1 text-sm text-gray-700">
                <div class="flex justify-between"><span>Kurir</span><span>{{ strtoupper($checkout->pengiriman->kurir) }}</span></div>
                <div class="flex justify-between"><span>Layanan</span><span>{{ $checkout->pengiriman->layanan }}</span></div>
                <div class="flex justify-between"><span>Ongkir</span><span>Rp{{ number_format($checkout->pengiriman->ongkir, 0, ',', '.') }}</span></div>
            </div>
        @else
            <p class="text-sm text-gray-400 italic">Kurir belum dipilih.</p>
        @endif
    </div>

    {{-- Tombol Checkout --}}
    <div class="text-center md:text-right pt-6">
        @if ($checkout->pengiriman && $checkout->pengiriman->kurir)
            <form action="{{ route('user.pembayaran.create', $checkout->id) }}" method="GET">
                <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition">
                    <i data-lucide="wallet" class="w-5 h-5"></i> Lanjut ke Pembayaran
                </button>
            </form>
        @else
            <p class="text-sm text-red-500">Silakan lengkapi alamat dan kurir terlebih dahulu.</p>
        @endif
    </div>
</div>

{{-- Lucide --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();

    const varianSelect = document.getElementById('select-varian');
    varianSelect?.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        document.getElementById('input-produk-id').value = selected.getAttribute('data-produk-id');
    });
    varianSelect?.dispatchEvent(new Event('change'));
</script>
@endsection
