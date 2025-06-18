<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaksi extends Model
{
    // Mengaktifkan fitur soft delete (penghapusan sementara)
    use SoftDeletes;

    protected $table = 'transaksi';

    protected $fillable = [
        'user_id',         // ID user yang melakukan transaksi
        'produk_id',       // ID produk yang dibeli
        'varian_id',       // ID varian produk (jika ada)
        'checkout_id',     // ID data checkout terkait
        'pengiriman_id',   // ID data pengiriman
        'pembayaran_id',   // ID data pembayaran
        'resi',
        'status',          // Status transaksi: diproses, dikirim, selesai, dibatalkan
    ];

    // Relasi: Transaksi dimiliki oleh satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Transaksi berhubungan dengan satu produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Relasi: Transaksi memiliki satu data checkout
    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }

    // Relasi: Transaksi memiliki satu data pengiriman
    public function pengiriman()
    {
        return $this->belongsTo(Pengiriman::class);
    }

    // Relasi: Transaksi memiliki satu data pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }

    // Relasi: Transaksi memiliki satu varian produk
    public function varian()
    {
        return $this->belongsTo(Varian::class);
    }
}
