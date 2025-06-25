<?php

namespace App\Services;

class WilayahService
{
    protected $originList;
    protected $provinces;

    public function __construct()
    {
        // Ambil data kota dari file cities.json sebagai daftar origin
        $this->originList = json_decode(file_get_contents(database_path('data/cities.json')), true);
        $this->provinces = json_decode(file_get_contents(database_path('data/provinces.json')), true);
    }

    /**
     * Ambil semua kota asal (origin)
     */
    public function getOriginList()
    {
        return collect($this->originList)->map(function ($item) {
            $originId = null;
            $originName = null;
            $provinceId = null;

            foreach ($item as $key => $value) {
                if (is_numeric($value) && $value != 11) {
                    $originId = $value;
                }

                if (is_numeric($key) && $key != "11") {
                    $provinceId = $item["11"] ?? null;
                }

                if (!is_numeric($key) && $key != "11") {
                    $originName = $value;
                }
            }

            // Jika belum ketemu nama kota (origin), ambil key-nya
            if (!$originName) {
                foreach ($item as $key => $value) {
                    if (!is_numeric($key) && $key != "11") {
                        $originName = $key;
                        break;
                    }
                }
            }

            return [
                'id' => $originId,
                'name' => $originName,
                'province_id' => $provinceId,
            ];
        })
        ->filter(fn($o) => $o['id'] !== null && $o['name'] !== null)
        ->sortBy('name')
        ->values()
        ->all();
    }

    /**
     * Cari origin berdasarkan ID
     */
    public function getOriginById($originId)
    {
        foreach ($this->getOriginList() as $origin) {
            if ((int) $origin['id'] === (int) $originId) {
                return $origin;
            }
        }
        return null;
    }
}
