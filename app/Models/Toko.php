<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Toko extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'toko';

    protected $fillable = [
        'nama_toko',
        'keterangan',
        'alamat',
        'origin',
        'foto_toko',
        'nomer_wa',
        'user_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($toko) {
            if (empty($toko->keterangan)) {
                $toko->keterangan = 'Keterangan belum ditambahkan.';
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }

    public function checkout()
    {
        return $this->hasMany(Checkout::class); 
    }

    public function pengiriman()
    {
        return $this->hasMany(Pengiriman::class);   
    }

    /**
     * Ambil data kota dari API Komerce.
     * Disimpan di cache selama 1 jam supaya tidak setiap kali request ke API.
     */
    private static function fetchOrigins()
    {
        $defaultKeywords = [
            'bandung', 'bandung_barat', 'bekasi', 'kabupaten_bekasi',
            'bogor', 'kabupaten_bogor', 'cimahi', 'cirebon', 'kabupaten_cirebon',
            'depok', 'garut', 'indramayu', 'karawang', 'kuningan',
            'majalengka', 'pangandaran', 'purwakarta', 'subang', 'sukabumi',
            'kabupaten_sukabumi', 'sumedang', 'tasikmalaya', 'kabupaten_tasikmalaya'
        ];

        $allCities = [];

        foreach ($defaultKeywords as $kw) {
            $response = Http::withHeaders([
                'x-api-key' => env('KOMERCE_API_KEY'),
                'Accept' => 'application/json',
            ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                'keyword' => $kw,
            ]);

            if ($response->ok() && isset($response['data'])) {
                $allCities = array_merge($allCities, $response['data']);
            }
        }

        // Hapus data kota duplikat berdasarkan 'id'
        $uniqueCities = collect($allCities)->unique('id')->values()->all();

        return $uniqueCities;
    }

    /**
     * Accessor untuk mendapatkan nama kota berdasarkan origin id.
     * Bisa dipanggil dengan $toko->city_name di view atau controller.
     */
    public function getCityNameAttribute()
    {
        $origins = Cache::remember('komerce_origins', 3600, function () {
            return self::fetchOrigins();
        });

        $city = collect($origins)->firstWhere('id', $this->origin);

        return $city['label'] ?? 'Kota tidak ditemukan';
    }
}
