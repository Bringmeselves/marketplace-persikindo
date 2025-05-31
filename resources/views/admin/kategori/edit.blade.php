@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Kategori</h1>

    <form action="{{ route('admin.kategori.update', $kategori->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama Kategori</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $kategori->name) }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-warning mt-3">Perbarui</button>
        <a href="{{ route('admin.kategori.index') }}" class="btn btn-secondary mt-3">Batal</a>
    </form>
</div>
@endsection
