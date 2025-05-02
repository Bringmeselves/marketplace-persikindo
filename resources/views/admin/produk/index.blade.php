@extends('layouts.app')

@section('title', 'Produk Admin')

@section('content')
    <h1>Produk - Admin</h1>
    <!-- Daftar Produk -->
    <a href="{{ route('admin.produk.create') }}">Tambah Produk</a>
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
                        <a href="{{ route('admin.produk.destroy', $item->id) }}" onclick="return confirm('Hapus Produk?')">Hapus</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
