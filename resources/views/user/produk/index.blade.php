@extends('layouts.app')

@section('title', 'Produk User')

@section('content')
    <h1>Produk Anda</h1>
    <a href="{{ route('user.produk.store') }}">Tambah Produk</a>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Loop produk -->
            @foreach ($produk as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>
                        <a href="{{ route('user.produk.destroy', $item->id) }}" onclick="return confirm('Hapus Produk?')">Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
