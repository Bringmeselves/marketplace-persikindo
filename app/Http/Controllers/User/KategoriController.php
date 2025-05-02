<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori untuk user memilih saat ingin menjual produk.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $kategori = Kategori::all(); // Ambil semua kategori
        return view('kategori.index', compact('kategori')); // Tampilkan daftar kategori
    }

    /**
     * Menampilkan form untuk memilih kategori saat menjual produk.
     *
     * @param int $produkId
     * @return \Illuminate\View\View
     */
    public function showForm(int $produkId)
    {
        $kategori = Kategori::all(); // Ambil semua kategori
        return view('produk.form_kategori', compact('kategori', 'produkId')); // Tampilkan form kategori
    }

    /**
     * Menyimpan kategori yang dipilih saat menjual produk.
     *
     * @param Request $request
     * @param int $produkId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, int $produkId)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id', 
        ]);

        $produk = Produk::findOrFail($produkId);
        $produk->kategori_id = $request->kategori_id; // Assign kategori ke produk
        $produk->save();

        return redirect()->route('produk.index')->with('success', 'Kategori produk berhasil disimpan!');
    }
}
