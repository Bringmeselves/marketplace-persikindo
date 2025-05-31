<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil daftar kategori untuk dropdown
        $kategori = Kategori::all();

        // Query produk dari user dengan role 'anggota'
        $query = Produk::whereHas('toko.user', function ($q) {
            $q->where('role', 'anggota');
        })->with('toko');

        // Filter berdasarkan kategori jika ada
        if ($request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        // Ambil produk dengan pagination (misal 20 per halaman)
        $produk = $query->paginate(20);

        // Kirim ke view
        return view('user.marketplace.index', compact('produk', 'kategori'));
    }
}
