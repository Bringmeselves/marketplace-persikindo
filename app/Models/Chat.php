<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    protected $table = 'chat';
    
    protected $fillable = [
        'user_id', 
        'toko_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function pesan()
    {
        return $this->hasMany(Pesan::class);
    }
}
