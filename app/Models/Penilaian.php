<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penilaian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'penilaian'; // custom nama tabel

    protected $fillable = [
        'produk_id',
        'user_id',
        'rating',
        'ulasan',
    ];

    /**
     * Relasi: Penilaian dimiliki oleh satu produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Relasi: Penilaian dibuat oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
