<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'user_id',
        'status',
        'bukti_pendaftaran',
        'tanggal_pengajuan',
        'nama_perusahaan',
        'legalitas',
        'nib',
        'npwp',
        'sertifikat_halal',
        'pirt',
    ];

    /**
     * Get the user that owns the Anggota.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}