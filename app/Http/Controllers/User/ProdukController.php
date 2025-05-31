<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Toko;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $produk = Produk::where('user_id', $user->id)->get();
        return view('user.produk.index', compact('produk'));
    }

    /**
     * Menampilkan form untuk membuat produk baru
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat produk.');
        }

        if (!$user->toko()->exists()) { // Periksa apakah pengguna memiliki toko
            return redirect()->route('user.toko.create')->with('error', 'Anda harus memiliki toko untuk menambahkan produk.');
        }

        $kategori = Kategori::all(); // Menampilkan daftar kategori produk
        return view('user.produk.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menambahkan produk.');
        }

        if (!$user->toko()->exists()) { // Periksa apakah pengguna memiliki toko
            return redirect()->route('user.toko.create')->with('error', 'Anda harus memiliki toko untuk menambahkan produk.');
        }

        // Validasi input
        $validated = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:1000',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Proses unggah gambar
        $gambarPath = $request->hasFile('gambar') 
            ? $request->file('gambar')->store('produk_gambar', 'public') 
            : null;

        // Menyimpan data produk baru
        Produk::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $gambarPath,
            'status' => 'aktif',
            'user_id' => $user->id,
            'kategori_id' => $request->kategori_id,
            'toko_id' => $user->toko->id, // Ambil toko_id dari toko pengguna
        ]);

        return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit produk
     */
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        $user = Auth::user();

        if ($produk->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }

        $kategori = Kategori::all();
        return view('user.produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Mengupdate data produk
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $user = Auth::user();

        if ($produk->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }

        // Validasi input
        $validated = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:1000',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_id' => 'required|exists:kategori,id',
        ]);

        if ($validated->fails()) {
            return redirect()->back()->withErrors($validated)->withInput();
        }

        // Proses unggah gambar jika ada
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('produk_gambar', 'public');
            $produk->gambar = $gambarPath;
        }

        // Update data produk
        $produk->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $user = Auth::user();

        if ($produk->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus produk ini.');
        }

        $produk->delete();
        return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Menampilkan halaman marketplace
     */
    public function marketplace()
    {
        $produk = Produk::all(); // Mengambil semua produk dari database
        return view('user.marketplace.index', compact('produk'));
    }
}