@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Kategori</h1>

    <ul>
        @forelse($kategori as $item)
            <li>{{ $item->nama_kategori }}</li>
        @empty
            <li>Tidak ada kategori.</li>
        @endforelse
    </ul>
</div>
@endsection
