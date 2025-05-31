@extends('layouts.app')

@section('content')
<h1>Input Data Pengiriman</h1>

@if(session('success'))
    <div style="color:green;">{{ session('success') }}</div>
@endif

<form action="{{ route('user.pengiriman.store', $checkout->id) }}" method="POST">
    @csrf

    <p><strong>Produk:</strong> {{ $produk->nama }}</p>
    <p><strong>Jumlah:</strong> {{ $checkout->jumlah }}</p>

    <label>Kurir:</label><br>
    <input type="text" name="kurir" value="{{ old('kurir') }}" required>
    @error('kurir')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <label>Layanan:</label><br>
    <input type="text" name="layanan" value="{{ old('layanan') }}" required>
    @error('layanan')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <label>Alamat Pengiriman:</label><br>
    <textarea name="alamat" required>{{ old('alamat') ?? $checkout->alamat_penerima }}</textarea>
    @error('alamat')
        <div style="color:red;">{{ $message }}</div>
    @enderror
    <br>

    <button type="submit">Lanjutkan ke Pembayaran</button>
</form>
@endsection
