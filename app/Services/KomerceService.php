<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class KomerceService
{
    public function fetchOrigins()
    {
        return Cache::remember('komerce_origins_cache', now()->addHours(12), function () {
            $defaultKeywords = [
                '40113', '40195', '40191', '40198', '40197', '40193', '40196',
                '40153', '40154', '40151', '40152', '40142', '40141',
                '40121', '40122', '40123', '40124', '40125', '40126', '40127',
                '40128', '40129', '40130', '40131', '40132', '40133',
                '40134', '40135', '40136', '40137', '40138', '40139', '40140',
                '40143', '40144', '40145', '40146', '40147', '40148',
                '40149', '40150', '40155', '40156', '40157', '40158',
                '40159', '40160', '40161', '40162', '40163', '40164', '40165',
                '40166',        
                '40111', '40311', '40551', '17111', '17510', '16111', '16910',
                '40511', '45111', '45611', '16411', '44111', '45211', '41311',
                '45511', '45411', '46396', '41111', '41211', '43111', '43311',
                '45311', '46111', '46411',
            ];

            $allCities = [];

            foreach ($defaultKeywords as $kw) {
                $response = Http::withHeaders([
                    'x-api-key' => env('KOMERCE_API_KEY'),
                    'Accept' => 'application/json',
                ])->timeout(10)->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                    'keyword' => $kw,
                ]);

                if ($response->ok() && isset($response['data'])) {
                    $allCities = array_merge($allCities, $response['data']);
                }
            }

            return collect($allCities)->unique('id')->values()->all();
        });
    }

    public function getCityNameById($id)
    {
        $origin = $this->fetchOrigins();
        $city = collect($origin)->firstWhere('id', $id);
        return $city['label'] ?? null;
    }

    // Ambil dan cache data kota tujuan dari API Komerce
    public function fetchCities()
    {
        return Cache::remember('komerce_cities_cache', now()->addHours(12), function () {
            $keywords = range(1, 9999); // bisa disesuaikan untuk jangkauan ID

            $allCities = [];

            foreach ($keywords as $kw) {
                $response = Http::withHeaders([
                    'x-api-key' => env('KOMERCE_API_KEY'),
                    'Accept' => 'application/json',
                ])->timeout(10)->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                    'keyword' => $kw,
                ]);

                if ($response->ok() && isset($response['data'])) {
                    $allCities = array_merge($allCities, $response['data']);
                }

                // Batasi iterasi agar tidak overload API
                if (count($allCities) >= 1000) break;
            }

            return collect($allCities)->unique('id')->values()->all();
        });
    }

    // Ambil nama kota dari ID tujuan (cities)
    public function getDestinationCityNameById($id)
    {
        $cities = $this->fetchCities();
        $city = collect($cities)->firstWhere('id', (string) $id);
        return $city['label'] ?? null;
    }
}