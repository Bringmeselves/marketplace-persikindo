<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;

class ProdukController extends Controller
{
    // Menampilkan semua produk beserta relasi user dan toko
    public function index()
    {
        $produk = Produk::with(['user', 'toko', 'kategori'])
            ->latest()
            ->get();

        return view('admin.produk.index', compact('produk'));
    }

    // Menghapus produk siapa pun
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
