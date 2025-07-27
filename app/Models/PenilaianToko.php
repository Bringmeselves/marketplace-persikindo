<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianToko extends Model
{
    use HasFactory;

    protected $table = 'penilaian_toko'; // pastikan sesuai dengan nama tabel

    protected $fillable = [
        'toko_id',
        'user_id',
        'rating',
        'ulasan',
    ];

    // Relasi ke toko
    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    // Relasi ke user yang memberi penilaian
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
