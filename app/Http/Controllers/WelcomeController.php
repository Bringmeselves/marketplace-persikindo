<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Kategori;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $kategori = Kategori::all();

        $query = Produk::whereHas('toko.user', function ($q) {
            $q->where('role', 'anggota');
        })->with('toko')
            ->withCount(['transaksi as jumlah_terjual' => function ($q) {
            $q->where('status', 'selesai');
        }]);


        $produk = $query->latest()->take(8)->get(); // ambil 8 produk terbaru

        return view('welcome', compact('produk', 'kategori'));
    }
}
