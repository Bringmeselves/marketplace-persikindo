@extends('layouts.app')

@section('content')
    <h2>Daftar Produk yang Terjual</h2>
    @forelse ($transaksi as $item)
        <div>
            <p>Produk: {{ $item->produk->nama }}</p>
            <p>Pembeli: {{ $item->user->name }}</p>
            <p>Status: {{ $item->status }}</p>
            <p>Resi: {{ $item->resi ?? 'Belum tersedia' }}</p>
        </div>
    @empty
        <p>Belum ada penjualan.</p>
    @endforelse
@endsection
