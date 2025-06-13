<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Varian extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'varian';

    protected $fillable = [
        'produk_id',
        'nama', 
        'stok', 
        'harga', 
        'gambar',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}