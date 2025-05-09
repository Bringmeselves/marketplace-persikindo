<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registrasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'registrasi';

    protected $fillable = [
        'user_id',
        'no_anggota',
        'nama_lengkap',
        'no_hp',
        'bukti_pendaftaran',
        'status', // pending, disetujui, ditolak
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Relasi: Registrasi dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}