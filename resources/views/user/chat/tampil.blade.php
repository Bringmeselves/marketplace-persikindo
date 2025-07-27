@extends('layouts.app')

@section('title', 'Pesan')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-4 sm:px-6 lg:px-8 space-y-8 text-gray-800">

    {{-- Judul Halaman --}}
    <div class="flex items-center justify-between pb-4 border-b">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-green-500">
                <img src="{{ optional($chat->toko)->foto_toko ? asset('storage/' . $chat->toko->foto_toko) : 'https://via.placeholder.com/150' }}" alt="Foto Toko">
                    alt="Foto Toko"
                    class="w-full h-full object-cover">
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                    {{ optional($chat->toko)->nama_toko ?? 'Toko tidak ditemukan' }}
                </h2>
                {{-- Jika ingin tambahan status atau info lain, bisa ditambahkan di sini --}}
            </div>
        </div>

        <a href="{{ route('user.chat.index') }}"
        class="inline-flex items-center gap-2 text-sm font-semibold text-green-600 hover:underline">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali
        </a>
    </div>

    {{-- Daftar Pesan --}}
<div class="bg-white border rounded-2xl shadow p-4 space-y-2 max-h-[400px] overflow-y-auto">
    @forelse($chat->pesan as $pesan)
        @php
            $isMe = auth()->id() === $pesan->user_id;
        @endphp

        {{-- Jika pesan adalah ringkasan transaksi --}}
        @if ($pesan->is_ringkasan_transaksi && $pesan->transaksi)
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] p-3 rounded-xl text-sm shadow
                            {{ $isMe ? 'bg-green-500 text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                    <div class="mb-1 font-semibold text-xs opacity-80 flex items-center gap-1">
                        <i data-lucide="shopping-bag" class="w-3 h-3"></i>
                        Transaksi #{{ $pesan->transaksi->id }}
                    </div>

                    {{-- Ringkasan Produk --}}
                    <div class="bg-white border border-gray-200 rounded-2xl p-3 shadow-sm mt-2">
                        @foreach ($pesan->transaksi->checkout->item as $item)
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-14 h-14 rounded-lg overflow-hidden bg-white border">
                                    <img src="{{ asset('storage/' . ($item->varian->gambar ?? $item->gambar ?? 'placeholder.png')) }}"
                                         class="w-full h-full object-cover" alt="Produk">
                                </div>
                                <div class="flex-1 text-sm">
                                    <div class="font-medium {{ $isMe ? 'text-white' : 'text-gray-800' }}">
                                        {{ $item->produk->nama }}
                                        @if ($item->varian)
                                            <span class="{{ $isMe ? 'text-white/80' : 'text-gray-500' }}">
                                                ({{ $item->varian->nama }})
                                            </span>
                                        @endif
                                    </div>
                                    <div class="{{ $isMe ? 'text-white/70' : 'text-gray-500' }}">
                                        Jumlah: {{ $item->jumlah }}
                                    </div>
                                    <div class="{{ $isMe ? 'text-white/70' : 'text-gray-500' }}">
                                        Harga: Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Timestamp --}}
                    <div class="mt-2 text-[11px] text-right {{ $isMe ? 'text-white/70' : 'text-gray-300' }}">
                        {{ $pesan->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        @else
            {{-- Pesan biasa --}}
            <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-[75%] p-3 rounded-xl text-sm shadow
                            {{ $isMe ? 'bg-green-500 text-white rounded-br-none' : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                    <div class="mb-1 font-semibold text-xs opacity-80 flex items-center gap-1">
                        <i data-lucide="user" class="w-3 h-3"></i>
                        {{ $pesan->user->name }}
                    </div>

                    {{-- Isi pesan teks --}}
                    @if ($pesan->isi_pesan)
                        <div>{{ $pesan->isi_pesan }}</div>
                    @endif

                    {{-- Tampilkan file jika ada --}}
                    @if ($pesan->file_path)
                        <div class="mt-2">
                            <p class="text-xs font-medium mb-1">
                                📎 <a href="{{ asset('storage/' . $pesan->file_path) }}"
                                     target="_blank"
                                     class="{{ $isMe ? 'text-white underline' : 'text-green-600 underline' }}">
                                     {{ $pesan->file_name }}
                                </a>
                            </p>

                            {{-- Preview gambar jika tipe image --}}
                            @if (str_starts_with($pesan->file_type, 'image/'))
                                <img src="{{ asset('storage/' . $pesan->file_path) }}"
                                     alt="Preview"
                                     class="max-h-48 rounded mt-1 border">
                            @endif
                        </div>
                    @endif

                    {{-- Timestamp --}}
                    <div class="mt-2 text-[11px] text-right {{ $isMe ? 'text-white/70' : 'text-gray-500' }}">
                        {{ $pesan->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        @endif

    @empty
        <p class="text-sm text-center text-gray-500">Belum ada pesan dalam percakapan ini.</p>
    @endforelse
</div>

    {{-- Ringkasan Produk dalam Transaksi --}}
    @if ($transaksi)
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 mb-4 shadow-sm">
            <h3 class="text-sm font-semibold text-gray-700 mb-2">Terkait Transaksi #{{ $transaksi->id }}</h3>
            @foreach ($transaksi->checkout->item as $item)
                <div class="flex items-center gap-4 mb-3">
                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-white border">
                        <img src="{{ asset('storage/' . ($item->varian->gambar ?? $item->gambar ?? 'placeholder.png')) }}"
                            class="w-full h-full object-cover" alt="Produk">
                    </div>
                    <div class="flex-1 text-sm">
                        <div class="font-medium text-gray-800">
                            {{ $item->produk->nama }}
                            @if ($item->varian)
                                <span class="text-gray-500">({{ $item->varian->nama }})</span>
                            @endif
                        </div>
                        <div class="text-gray-500">Jumlah: {{ $item->jumlah }}</div>
                        <div class="text-gray-500">Harga: Rp{{ number_format($item->total_harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Form Kirim Pesan --}}
    <form action="{{ route('user.kirimPesan', $chat->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white border rounded-2xl shadow p-4 space-y-2">
        @csrf

        {{-- Teks Pesan --}}
        <div class="relative">
            @php
                $defaultMessage = session('default_message');
            @endphp

            <textarea name="isi_pesan"
                    class="w-full border border-gray-300 rounded-full py-2 pl-4 pr-10 resize-none focus:outline-none focus:ring-2 focus:ring-green-400 text-sm"
                    rows="1"
                    placeholder="Tulis pesan...">{{ old('isi_pesan', $defaultMessage) }}</textarea>
            <div class="absolute right-3 top-2.5 text-gray-400">
                <i data-lucide="pen-line" class="w-4 h-4"></i>
            </div>
        </div>

        {{-- Upload File --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm cursor-pointer text-gray-600">
                <i data-lucide="paperclip" class="w-4 h-4"></i>
                <span>Upload File</span>
                <input type="file" name="file" class="hidden">
            </label>

            <button type="submit"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-green-500 text-white hover:bg-green-600 shadow">
                <i data-lucide="send" class="w-4 h-4"></i>
            </button>
        </div>
    </form>
</div>

{{-- Inisialisasi Lucide Icons --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
</script>
@endsection
