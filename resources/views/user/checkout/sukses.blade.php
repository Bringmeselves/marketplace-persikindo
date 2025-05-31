@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h2>Transaksi Berhasil!</h2>
    <p>Terima kasih! Silakan lanjutkan ke pembayaran sesuai petunjuk di dashboard.</p>
    <a href="{{ route('user.dashboard') }}" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
</div>
@endsection
