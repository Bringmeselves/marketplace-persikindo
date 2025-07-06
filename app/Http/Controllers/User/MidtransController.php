<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\User\TransaksiController;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Ambil data dari callback
        $payload = $request->all();
        Log::info('Midtrans Callback:', $payload); // Optional: Log untuk debug

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        if (!$orderId || !$transactionStatus) {
            return response()->json(['error' => 'Invalid callback'], 400);
        }

        // Cari data pembayaran berdasarkan order_id
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return response()->json(['error' => 'Pembayaran tidak ditemukan'], 404);
        }

        // Update status pembayaran
        $pembayaran->status_pembayaran = $transactionStatus;
        $pembayaran->save();

        // Buat transaksi jika status sukses dan belum ada transaksi
        if (in_array($transactionStatus, ['settlement', 'capture'])) {
            $transaksiSudahAda = \App\Models\Transaksi::where('checkout_id', $pembayaran->checkout_id)->exists();

            if (!$transaksiSudahAda) {
                // Login user sementara untuk store()
                auth()->loginUsingId($pembayaran->user_id);

                // Panggil controller transaksi
                $transaksiController = new TransaksiController();
                $transaksiController->store($pembayaran->checkout_id);

                auth()->logout();
            }
        }

        return response()->json(['message' => 'Callback diterima'], 200);
    }
}
