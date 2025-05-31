@extends('layouts.app')

@section('content')
<h2>Update Status Pembayaran</h2>

@if(session('error'))
    <div style="color:red;">{{ session('error') }}</div>
@endif

<form action="{{ route('user.pembayaran.updateStatus', $pembayaran->checkout_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="status">Status Pembayaran:</label>
        <select name="status" id="status" required>
            <option value="belum_bayar" {{ $pembayaran->status == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
            <option value="sudah_bayar" {{ $pembayaran->status == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
            <option value="gagal" {{ $pembayaran->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
        </select>
    </div>

    <button type="submit">Update Status</button>
</form>
@endsection
