<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = env('RAJAONGKIR_API_KEY');
        $this->baseUrl = env('RAJAONGKIR_API_URL') . '/api';
    }

    /**
     * Ambil semua provinsi dari RajaOngkir.
     */
    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->get("{$this->baseUrl}/province");

        return response()->json($response->json());
    }

    /**
     * Ambil semua kota berdasarkan ID provinsi.
     */
    public function getCities($province_id)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->get("{$this->baseUrl}/city", [
            'province' => $province_id,
        ]);

        return response()->json($response->json());
    }

    /**
     * Hitung ongkir berdasarkan origin, destination, weight, dan courier.
     */
    public function checkShipping(Request $request)
    {
        $request->validate([
            'origin' => 'required|integer',
            'destination' => 'required|integer',
            'weight' => 'required|integer',
            'courier' => 'required|string',
        ]);

        $response = Http::withHeaders([
            'key' => $this->apiKey,
        ])->post("{$this->baseUrl}/cost", [
            'origin' => $request->origin,
            'destination' => $request->destination,
            'weight' => $request->weight,
            'courier' => $request->courier,
        ]);

        return response()->json($response->json());
    }
}
