<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'checkout';

    protected $fillable = [
        'user_id', 
        'toko_id', 
        'produk_id',
        'total_harga', 
        'status'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function varian()
    {
        return $this->belongsTo(Varian::class);
    }

    public function item()
    {
        return $this->hasMany(CheckoutItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function pengiriman()
    {
        return $this->hasOne(Pengiriman::class);
    }

}
