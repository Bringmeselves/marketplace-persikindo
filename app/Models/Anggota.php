<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Anggota extends Model
{

    protected $table = 'anggota';
    
    protected $fillable = [
        'user_id', // Relasi ke tabel users
        'nama_lengkap',
        'nik',
        'status',
        'bukti_pendaftaran',
        'tanggal_pengajuan',
        'nama_perusahaan',
        'legalitas',
        'nib',
        'npwp',
        'sertifikat_halal',
        'pirt',
        'catatan'
    ];

    /**
     * Relasi belongsTo ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}