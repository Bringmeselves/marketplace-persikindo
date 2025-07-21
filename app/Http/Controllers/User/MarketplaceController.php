<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MarketplaceController extends Controller
{
    /**
     * Mengambil daftar origin (kota) dari API Komerce
     */
    private function fetchOrigins()
    {
        // Gunakan cache selama 12 jam (720 menit)
        return Cache::remember('komerce_origins_cache', now()->addHours(12), function () {
            $defaultKeywords = [
                // Kode Pos Bandung dan kota lain (seperti yang kamu punya)
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

        // Filter produk berdasarkan kategori jika ada
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        $produk = $query->paginate(20);

        // Ambil daftar kota (origin) dari API Komerce
        $origins = $this->fetchOrigins();

        // Ambil anggota login (jika ada)
        $anggota = null;
        if (auth()->check()) {
            $anggota = \App\Models\Anggota::where('user_id', auth()->id())
                ->where('status', 'rejected')
                ->whereNotNull('catatan')
                ->latest()
                ->first();

            if ($anggota && !session()->has('catatan_penolakan')) {
                session()->flash('catatan_penolakan', $anggota->catatan);
            }
        }

        // Kirim ke view
        return view('user.marketplace.index', compact('produk', 'kategori', 'origins', 'anggota'));
    }
}
