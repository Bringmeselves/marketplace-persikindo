<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registrasi extends Model
{
    use SoftDeletes; 
    protected $table = 'registrasi'; // Nama tabel (pastikan sesuai)
    
    protected $fillable = [
        'user_id',
        'no_anggota',
        'nama_lengkap',
        'no_hp',
        'bukti_pendaftaran', // opsional: path file bukti
        'status', // pending, disetujui, ditolak
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
