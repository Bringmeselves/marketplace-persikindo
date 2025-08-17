<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request; 
use Mpdf\Mpdf;

class TransaksiController extends Controller
{
    public function index()
    {
        $transaksiList = Transaksi::with([
                'checkout',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.item.produk.toko',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
                'user',
            ])
            ->latest()
            ->paginate(10);

        return view('admin.transaksi.index', compact('transaksiList'));
    }

    public function show($id)
    {
        $transaksi = Transaksi::with([
                'checkout',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.item.produk.toko',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
                'user',
            ])
            ->findOrFail($id);

        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function download($id, Request $request)
    {
        $transaksi = Transaksi::with([
                'checkout',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.item.produk.toko',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
                'user',
            ])
            ->findOrFail($id);

        $html = view('admin.transaksi.download', compact('transaksi'))->render();

        $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
        $mpdf->WriteHTML($html);

        $fileName = "transaksi_{$transaksi->id}.pdf";

        if ($request->get('mode') === 'download') {
            // ambil PDF sebagai string
            $pdfContent = $mpdf->Output($fileName, 'S');

            // kirim response untuk download (IDM tidak akan intercept karena bukan langsung 'D')
            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', "attachment; filename={$fileName}");
        }

        // default: preview inline
        return $mpdf->Output($fileName, 'I');
    }
}
