<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Toko extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'toko';

    protected $fillable = [
        'nama_toko',
        'slug',
        'alamat',
        'foto_toko',
        'user_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Auto generate slug dari nama_toko jika belum diisi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($toko) {
            if (empty($toko->slug)) {
                $toko->slug = Str::slug($toko->nama_toko);
            }
        });
    }

    /**
     * Relasi dengan model User
     * Setiap toko dimiliki oleh satu pengguna (penjual).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi dengan model Produk
     * Setiap toko dapat menjual banyak produk.
     */
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}