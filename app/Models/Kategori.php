<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori';

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug dari name jika belum diisi
        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->name);
            }
        });
    }

    /**
     * Relasi: satu kategori memiliki banyak produk
     */
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}