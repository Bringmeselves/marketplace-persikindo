<?php
use App\Http\Controllers\User\MidtransController;
use App\Services\WahaService;

Route::get('/test-waha', function () {
    $waha = new WahaService();

    // ✅ GANTI nomor WA kamu sendiri!
    $nomor = '6281234567890';
    $pesan = '✅ Tes koneksi dari Laravel ke WAHA berhasil.';

    $res = $waha->sendText($nomor, $pesan);

    return response()->json($res);
});

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
