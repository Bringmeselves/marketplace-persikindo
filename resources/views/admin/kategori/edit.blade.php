@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Kategori: {{ $kategori->nama_kategori }}</h1>

    <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control"
                   value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
