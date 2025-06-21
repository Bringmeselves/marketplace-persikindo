<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Toko;
use App\Models\User;
use App\Models\Varian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk milik user (anggota)
     */
    public function index()
    {
        $user = Auth::user();

        // Cek apakah user adalah anggota
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil semua produk milik user
        $produk = Produk::where('user_id', $user->id)->get();

        return view('user.produk.index', compact('produk'));
    }

    /**
     * Menampilkan form tambah produk baru
     */
    public function create()
    {
        $user = Auth::user();

        // Cek hak akses user
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat produk.');
        }

        // Pastikan user sudah punya toko
        if (!$user->toko()->exists()) {
            return redirect()->route('user.toko.create')->with('error', 'Anda harus memiliki toko untuk menambahkan produk.');
        }

        // Ambil semua kategori untuk dropdown form
        $kategori = Kategori::all();

        return view('user.produk.create', compact('kategori'));
    }

    /**
     * Menyimpan produk dan varian ke database
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi input form
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:1000',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'kategori_id' => 'required|exists:kategori,id',

            // Validasi array varian
            'varian.nama.*' => 'required|string|max:255',
            'varian.stok.*' => 'required|integer|min:0',
            'varian.harga.*' => 'required|numeric|min:0',
            'varian.gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Hitung total stok varian
        $totalStokVarian = array_sum($request->varian['stok']);

        // Validasi total stok varian tidak boleh melebihi stok utama produk
        if ($totalStokVarian > $request->stok) {
            return redirect()->back()->withInput()->withErrors([
                'varian.stok' => 'Jumlah total stok varian tidak boleh melebihi stok utama produk.'
            ]);
        }

        // Simpan gambar utama produk jika diunggah
        $gambarPath = $request->hasFile('gambar')
            ? $request->file('gambar')->store('produk_gambar', 'public')
            : null;

        // Simpan data produk utama
        $produk = Produk::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'gambar' => $gambarPath,
            'status' => 'aktif',
            'user_id' => $user->id,
            'kategori_id' => $request->kategori_id,
            'toko_id' => $user->toko->id,
        ]);

        // Simpan semua varian yang diinput
        if ($request->has('varian')) {
            foreach ($request->varian['nama'] as $i => $namaVarian) {
                $gambarVarian = null;

                // Simpan gambar varian jika ada
                if ($request->hasFile("varian.gambar.$i")) {
                    $gambarVarian = $request->file("varian.gambar.$i")->store('varian_gambar', 'public');
                }

                // Simpan data varian dan relasikan ke produk
                $produk->varian()->create([
                    'nama' => $namaVarian,
                    'stok' => $request->varian['stok'][$i],
                    'harga' => $request->varian['harga'][$i],
                    'gambar' => $gambarVarian,
                ]);
            }
        }

        return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])->with('success', 'Produk dan variannya berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit produk
     */
    public function edit($id)
    {
        // Ambil produk beserta variannya
        $produk = Produk::with('varian')->findOrFail($id);
        $user = Auth::user();

        // Pastikan hanya pemilik yang bisa mengedit
        if ($produk->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }

        $kategori = Kategori::all();
        return view('user.produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update produk utama (belum termasuk edit varian)
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $user = Auth::user();

        // Cek hak akses
        if ($produk->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini.');
        }

        // Validasi input update
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

        // Simpan gambar baru jika diupload
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
     * Menghapus produk dan seluruh variannya
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $user = Auth::user();

        // Cek kepemilikan
        if ($produk->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus produk ini.');
        }

        // Hapus produk (otomatis ikut soft delete varian jika pakai relasi cascade + SoftDeletes)
        $produk->delete();

        return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Menampilkan semua produk di halaman marketplace
     */
    public function marketplace()
    {
        // Ambil semua produk aktif beserta variannya
        $produk = Produk::with('varian')->where('status', 'aktif')->get();

        return view('user.marketplace.index', compact('produk'));
    }
}
