<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Checkout extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'checkout';

    protected $fillable = [
        'user_id', 
        'toko_id', 
        'total_harga', 
        'status'
    ];

    public function item()
    {
        return $this->hasMany(CheckoutItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}
