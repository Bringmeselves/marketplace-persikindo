<?php
use App\Http\Controllers\User\MidtransController;

Route::post('/midtrans/callback', [MidtransController::class, 'callback']);
