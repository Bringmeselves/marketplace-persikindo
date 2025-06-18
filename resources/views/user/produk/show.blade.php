@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Informasi Produk --}}
    <h2>{{ $produk->nama }}</h2>
    <p>{{ $produk->deskripsi }}</p>
    <p>Harga: Rp{{ number_format($produk->harga, 0, ',', '.') }}</p>

    {{-- Form Penilaian --}}
    @auth
    <div class="mt-4">
        <h4>Berikan Penilaian</h4>
        <form action="{{ route('penilaian.store', $produk->id) }}" method="POST">
            @csrf
            <input type="hidden" name="produk_id" value="{{ $produk->id }}">

            {{-- Rating --}}
            <div class="mb-2">
                <label for="rating">Rating (1-5):</label>
                <select name="rating" id="rating" class="form-control" required>
                    <option value="">Pilih Rating</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            {{-- Ulasan --}}
            <div class="mb-2">
                <label for="ulasan">Ulasan:</label>
                <textarea name="ulasan" id="ulasan" class="form-control" rows="3" placeholder="Tulis ulasan (opsional)"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Kirim Penilaian</button>
        </form>
    </div>
    @else
    <p><a href="{{ route('login') }}">Login</a> untuk memberikan penilaian.</p>
    @endauth

    {{-- Daftar Penilaian --}}
    <div class="mt-4">
        <h4>Penilaian Pengguna</h4>
        @forelse($produk->penilaian as $item)
            <div class="border rounded p-2 mb-2">
                <strong>{{ $item->user->name }}</strong> 
                <span> - Rating: {{ $item->rating }}/5</span>
                <p>{{ $item->ulasan }}</p>
            </div>
        @empty
            <p>Belum ada penilaian.</p>
        @endforelse
    </div>

</div>
@endsection
