<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
