<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RajaOngkirController;

Route::prefix('rajaongkir')->group(function () {
    Route::get('/provinces', [RajaOngkirController::class, 'getProvinces']);
    Route::get('/cities/{province_id}', [RajaOngkirController::class, 'getCities']);
    Route::post('/check-shipping', [RajaOngkirController::class, 'checkShipping']);
});
