<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjang';

    protected $fillable = [
        'user_id',
        'produk_id',
        'varian_id',
        'toko_id',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'gambar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function varian()
    {
        return $this->belongsTo(Varian::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}
