<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toko extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'toko';

    protected $fillable = [
        'nama_toko',
        'keterangan',
        'alamat',
        'cities',
        'foto_toko',
        'nomer_wa',
        'user_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Auto generate keterangan jika belum diisi
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($toko) {
            if (empty($toko->keterangan)) {
                $toko->keterangan = 'Keterangan belum ditambahkan.';
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

    public function getCityNameAttribute()
    {
        $wilayahService = app(\App\Services\WilayahService::class);
        return optional($wilayahService->getCityById($this->cities))['name'] ?? null;
    }

    public function getProvinceNameAttribute()
    {
        $wilayahService = app(\App\Services\WilayahService::class);
        return optional($wilayahService->getProvinceById($this->provinces))['name'] ?? null;
    }

}
