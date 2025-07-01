@extends('layouts.app')

@section('content')
<div class="p-6 bg-white rounded-xl shadow">
    <h2 class="text-xl font-semibold mb-4">Pembayaran Midtrans</h2>

    <p>Total: <strong>Rp{{ number_format($checkout->item->sum('total_harga') + ($checkout->pengiriman->ongkir ?? 0)) }}</strong></p>

    <button id="pay-button" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Bayar Sekarang</button>
</div>

<script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
  document.getElementById('pay-button').onclick = function () {
    snap.pay("{{ $snapToken }}", {
      onSuccess: function(result) {
        window.location.href = "/user/pembayaran/success/{{ $checkout->id }}";
      },
      onPending: function(result) {
        window.location.href = "/user/pembayaran/pending/{{ $checkout->id }}";
      },
      onError: function(result) {
        alert("Pembayaran gagal. Silakan coba lagi.");
      }
    });
  };
</script>
@endsection
