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
        'berat',
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
        return $this->belongsTo(Toko::class)->withTrashed();
    }

    /**
     * Relasi: Produk memiliki banyak Transaksi
     */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class)->where('status', 'selesai');
    }

    /**
     * Relasi: Produk memiliki banyak Varian
     */
    public function varian()
    {
        return $this->hasMany(Varian::class);
    }
    
    /**
     * Relasi: Produk memiliki banyak Penilaian
     */
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class);
    }
}
