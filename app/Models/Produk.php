<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produk';

    protected $fillable = [
        'nama',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'status',
        'user_id',
        'kategori_id',
        'toko_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Relasi: Produk dimiliki oleh seorang User (penjual)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Produk termasuk dalam satu Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi: Produk dimiliki oleh satu Toko
     */
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}