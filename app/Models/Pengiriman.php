<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class Pengiriman extends Model
{
    use SoftDeletes;

    protected $table = 'pengiriman';

    protected $fillable = [
        'user_id',
        'toko_id',
        'checkout_id',
        'nama_lengkap',
        'nomor_wa',
        'alamat_penerima',
        'cities',
        'kode_pos',
        'catatan',
        'kurir',
        'layanan',
        'ongkir',
        'status_pengiriman',
    ];

     // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Toko
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    // Relasi ke Checkout
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

     // Accessor untuk mendapatkan nama kota dari ID 'cities'
    public function getCityNameAttribute()
    {
        \Log::info('--- Accessor getCityNameAttribute dipanggil ---');
        \Log::info('Cek CITIES:', ['cities' => $this->cities]);

        try {
            // Pakai service KomerceService
            $komerceService = app(\App\Services\KomerceService::class);
            $cityName = $komerceService->getCityNameById($this->cities);

            \Log::info('Nama Kota Ditemukan:', ['city_name' => $cityName]);

            return $cityName ?? '-';
        } catch (\Exception $e) {
            \Log::error('Gagal ambil city name dari service: ' . $e->getMessage());
            return '-';
        }
    }
}
