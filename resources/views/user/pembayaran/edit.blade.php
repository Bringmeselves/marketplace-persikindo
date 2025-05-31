@extends('layouts.app')

@section('content')
<h2>Update Info Pembayaran</h2>

@if(session('error'))
    <div style="color:red;">{{ session('error') }}</div>
@endif

<form action="{{ route('user.pembayaran.updatePaymentInfo', $pembayaran->checkout_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div>
        <label for="payment_reference">Payment Reference:</label>
        <input type="text" name="payment_reference" id="payment_reference" value="{{ old('payment_reference', $pembayaran->payment_reference) }}" required>
        @error('payment_reference')
            <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="paid_at">Tanggal Bayar:</label>
        <input type="datetime-local" name="paid_at" id="paid_at" value="{{ old('paid_at', $pembayaran->paid_at ? $pembayaran->paid_at->format('Y-m-d\TH:i') : '') }}" required>
        @error('paid_at')
            <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <div>
        <label for="status">Status:</label>
        <select name="status" id="status" required>
            <option value="belum_bayar" {{ $pembayaran->status == 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
            <option value="sudah_bayar" {{ $pembayaran->status == 'sudah_bayar' ? 'selected' : '' }}>Sudah Bayar</option>
            <option value="gagal" {{ $pembayaran->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
        </select>
        @error('status')
            <div style="color:red;">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit">Update Pembayaran</button>
</form>
@endsection
