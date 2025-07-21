<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckoutItem extends Model
{
    use SoftDeletes;

    protected $table = 'checkout_item';

    protected $fillable = [
        'user_id',
        'produk_id',
        'varian_id',
        'checkout_id',
        'toko_id',
        'jumlah',
        'harga_satuan',
        'gambar',
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

    // Relasi ke varian
    public function varian()
    {
        return $this->belongsTo(Varian::class, 'varian_id');
    }

    // Relasi ke checkout
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }
}
