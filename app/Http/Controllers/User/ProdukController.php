<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:user']); // ← Tambahkan ini
    }

    // Menampilkan semua produk milik user yang sedang login
    public function index()
    {
        $produk = Produk::with(['kategori', 'toko'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($produk);
    }

    // Menyimpan produk baru oleh user
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'status' => 'required|in:aktif,nonaktif',
            'kategori_id' => 'required|exists:kategori,id',
            'toko_id' => 'required|exists:tokos,id',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id(); // otomatis pakai user yang login

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk = Produk::create($data);

        return response()->json($produk, 201);
    }

    // Mengupdate produk milik user
    public function update(Request $request, $id)
    {
        $produk = Produk::where('user_id', Auth::id())->findOrFail($id); // hanya miliknya

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'status' => 'required|in:aktif,nonaktif',
            'kategori_id' => 'required|exists:kategori,id',
            'toko_id' => 'required|exists:toko,id',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return response()->json($produk);
    }

    // Menghapus produk milik user
    public function destroy($id)
    {
        $produk = Produk::where('user_id', Auth::id())->findOrFail($id);
        $produk->delete();

        return response()->json(['message' => 'Produk berhasil dihapus']);
    }
}
