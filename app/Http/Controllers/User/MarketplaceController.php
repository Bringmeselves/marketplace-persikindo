<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MarketplaceController extends Controller
{
    /**
     * Mengambil daftar origin (kota) dari API Komerce
     */
    private function fetchOrigins()
    {
        // Panggil API Komerce untuk mendapatkan daftar kota tujuan berdasarkan keyword
        $response = Http::withHeaders([
            'x-api-key' => env('KOMERCE_API_KEY'),
            'Accept' => 'application/json',
        ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
            'keyword' => 'bandung', 'bandung_barat', 'bekasi', 'kabupaten_bekasi',
            'bogor', 'kabupaten_bogor', 'cimahi', 'cirebon', 'kabupaten_cirebon',
            'depok', 'garut', 'indramayu', 'karawang', 'kuningan',
            'majalengka', 'pangandaran', 'purwakarta', 'subang', 'sukabumi',
            'kabupaten_sukabumi', 'sumedang', 'tasikmalaya', 'kabupaten_tasikmalaya'
        ]);

        // Jika respons OK dan data tersedia, kembalikan data kota
        if ($response->ok() && isset($response['data'])) {
            return $response['data']; // array kota
        }

        // Jika gagal, kembalikan array kosong
        return [];
    }

    /**
     * Mencari nama kota berdasarkan ID dari hasil fetchOrigins
     */
    private function getCityNameById($id, $origins)
    {
        // Cari kota berdasarkan ID dalam daftar origins
        $city = collect($origins)->firstWhere('id', $id);
        return $city['label'] ?? 'Unknown City'; // Jika tidak ditemukan, kembalikan 'Unknown City'
    }

    /**
     * Menampilkan halaman utama marketplace
     */
    public function index(Request $request)
    {
        // Ambil semua kategori
        $kategori = Kategori::all();

        // Ambil produk yang hanya dijual oleh toko milik user dengan role 'anggota'
        $query = Produk::whereHas('toko.user', function ($q) {
            $q->where('role', 'anggota');
        })->with('toko');

        // Filter produk berdasarkan kategori jika ada permintaan filter
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        // Paginasi hasil produk
        $produk = $query->paginate(20);

        // Ambil daftar kota (origin) dari API Komerce
        $origins = $this->fetchOrigins();

        // Tampilkan view marketplace dengan data produk, kategori, dan origins
        return view('user.marketplace.index', compact('produk', 'kategori', 'origins'));
    }
}
