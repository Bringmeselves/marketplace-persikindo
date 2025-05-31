<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use SoftDeletes;

    protected $table = 'checkout';

    protected $fillable = [
        'user_id',
        'produk_id',
        'toko_id',
        'nama_lengkap',
        'nomor_wa',
        'alamat_penerima',
        'jumlah',
        'total_harga',
        'status',
    ];

    // Relasi ke user sebagai pembeli
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke produk yang dibeli
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Relasi ke toko/penjual (juga user)
    public function toko()
    {
        return $this->belongsTo(User::class, 'toko_id');
    }

    // Relasi ke pembayaran
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    // Relasi ke pengiriman
    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }
}
