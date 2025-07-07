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
        'produk_id',
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

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
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
            $response = Http::withHeaders([
                'x-api-key' => env('KOMERCE_API_KEY'),
                'Accept' => 'application/json',
            ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                'keyword' => $this->cities,
            ]);

            \Log::info('Cek RESPONSE:', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->ok() && isset($response['data'][0])) {
                return $response['data'][0]['city_name'] ?? '-';
            }

            return '-';
        } catch (\Exception $e) {
            \Log::error('Gagal ambil city name: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return '-';
        }
    }
}
