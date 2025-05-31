@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pembayaran</h2>
    <p>Order ID: TRX-{{ $transaksi->id }}</p>
    <p>Total Pembayaran: <strong>Rp{{ number_format($transaksi->total_harga) }}</strong></p>

    <button id="pay-button" class="btn btn-success">Bayar Sekarang</button>
</div>
@endsection

@section('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    var snapToken = "{{ $snapToken }}";

    document.getElementById('pay-button').addEventListener('click', function () {
        window.snap.pay(snapToken, {
            onSuccess: function (result) {
                alert("Pembayaran berhasil!");
                console.log(result);
                window.location.href = "{{ route('user.transaksi.sukses') }}";
            },
            onPending: function (result) {
                alert("Menunggu pembayaran...");
                console.log(result);
            },
            onError: function (result) {
                alert("Pembayaran gagal!");
                console.log(result);
            }
        });
    });
</script>
@endsection
