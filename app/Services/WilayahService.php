<?php

namespace App\Services;

class WilayahService
{
    protected $cities;
    protected $provinces;

    public function __construct()
    {
        $this->cities = json_decode(file_get_contents(database_path('data/cities.json')), true);
        $this->provinces = json_decode(file_get_contents(database_path('data/provinces.json')), true);
    }

    /**
     * Ambil semua kota
     */
    public function getCities()
    {
        return collect($this->cities)->map(function ($item) {
            $cityId = null;
            $cityName = null;
            $provinceId = null;

            foreach ($item as $key => $value) {
                if (is_numeric($value) && $value != 11) {
                    $cityId = $value;
                }

                if (is_numeric($key) && $key != "11") {
                    $provinceId = $item["11"] ?? null;
                }

                if (!is_numeric($key) && $key != "11") {
                    $cityName = $value;
                }
            }

            // Jika belum ketemu nama kota, ambil key-nya
            if (!$cityName) {
                foreach ($item as $key => $value) {
                    if (!is_numeric($key) && $key != "11") {
                        $cityName = $key;
                        break;
                    }
                }
            }

            return [
                'id' => $cityId,
                'name' => $cityName,
                'province_id' => $provinceId,
            ];
        })
        ->filter(fn($c) => $c['id'] !== null && $c['name'] !== null)
        ->sortBy('name')
        ->values()
        ->all();
    }

    /**
     * Cari kota berdasarkan ID
     */
    public function getCityById($cityId)
    {
        foreach ($this->getCities() as $city) {
            if ((int) $city['id'] === (int) $cityId) {
                return $city;
            }
        }
        return null;
    }
}
