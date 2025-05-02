@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Kategori ke Produk: {{ $produk->nama }}</h1>

    <form action="{{ route('produk.store_kategori', $produk->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="kategori_id" class="form-label">Pilih Kategori</label>
            <select name="kategori_id" id="kategori_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategoriList as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
