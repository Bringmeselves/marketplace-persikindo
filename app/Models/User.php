<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi massal.
     */
    protected $fillable = [
        'name', // Nama pengguna
        'email', // Email pengguna
        'password', // Password pengguna
        'email_verified_at', // Tanggal verifikasi email
        'remember_token', // Token untuk mengingat pengguna
        'role', // ID role 
        'status', // Status pengguna (aktif/non-aktif
        'approved', // Kolom baru untuk status persetujuan anggota
    ];
    /**
     * Relasi dengan model Toko
     * Setiap pengguna (penjual) memiliki satu toko.
     */
    public function toko()
    {
        return $this->hasOne(Toko::class);
    }
    /**
     * Relasi one-to-one dengan model Anggota
     */
    public function anggota()
    {
        return $this->hasOne(Anggota::class);
    }

    /**
     * Relasi: User memiliki banyak produk
     */
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

    // Tambahkan di app/Models/User.php
    public function getIsApprovedAttribute()
    {
        return (bool) $this->approved;
    }
   
}