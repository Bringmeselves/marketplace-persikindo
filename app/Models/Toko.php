<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Toko extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'toko';

    protected $fillable = [
        'nama_toko',
        'keterangan',
        'alamat',
        'origin',
        'foto_toko',
        'nomer_wa',
        'saldo',
        'user_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

    public function checkout()
    {
        return $this->hasMany(Checkout::class); 
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class);   
    }

    public function transaksiMasuk()
    {
        return \App\Models\Transaksi::whereHas('produk', function ($query) {
            $query->where('toko_id', $this->id);
        });
    }

    // Relasi ke PenilaianToko
    public function penilaian()
    {
        return $this->hasMany(PenilaianToko::class, 'toko_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($toko) {
            if (empty($toko->keterangan)) {
                $toko->keterangan = 'Keterangan belum ditambahkan.';
            }
        });
    }

}

