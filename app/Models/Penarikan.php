<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penarikan extends Model
{
    use SoftDeletes;

    protected $table = 'penarikan';

    protected $fillable = [
        'toko_id',
        'jumlah',
        'rekening_tujuan',
        'status',
        'catatan',
        'bukti_transfer',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}
