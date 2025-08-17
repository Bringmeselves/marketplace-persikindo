<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transaksi #{{ $transaksi->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
    </style>
</head>
<body>
    <h2>Detail Transaksi #{{ $transaksi->id }}</h2>
    <p><b>Pembeli:</b> {{ $transaksi->user->name }}</p>
    <p><b>Status:</b> {{ $transaksi->status }}</p>
    <p><b>Tanggal:</b> {{ $transaksi->created_at->format('d-m-Y H:i') }}</p>

    <h3>Produk</h3>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Varian</th>
                <th>Jumlah</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->checkout->item as $item)
                <tr>
                    <td>{{ $item->produk->nama }}</td>
                    <td>{{ $item->varian->nama ?? '-' }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Pengiriman</h3>
    <p>{{ $transaksi->pengiriman->alamat ?? '-' }}</p>
    <p>Kurir: {{ $transaksi->pengiriman->kurir ?? '-' }}</p>

    <h3>Pembayaran</h3>
    <p>Metode: {{ $transaksi->pembayaran->metode ?? '-' }}</p>
    <p>Total: Rp{{ number_format($transaksi->total, 0, ',', '.') }}</p>
</body>
</html>
