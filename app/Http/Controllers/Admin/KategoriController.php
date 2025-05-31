<?php

namespace App\Http\Controllers\Admin;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    /**
     * Menampilkan daftar kategori untuk admin.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $kategori = Kategori::all(); // Ambil semua kategori
        return view('admin.kategori.index', compact('kategori')); // Tampilkan daftar kategori
    }

    /**
     * Menampilkan form untuk menambah kategori.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Menyimpan kategori baru.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:kategori,name|max:255', // Validasi nama kategori
        ]);

        Kategori::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit kategori.
     *
     * @param Kategori $kategori
     * @return \Illuminate\View\View
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui kategori yang ada.
     *
     * @param Request $request
     * @param Kategori $kategori
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'name' => 'required|max:255|unique:kategori,name,' . $kategori->id, // Pastikan nama unik kecuali untuk kategori yang sedang diedit
        ]);

        $kategori->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Menghapus kategori.
     *
     * @param Kategori $kategori
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Kategori $kategori)
    {
        $kategori->delete(); // Hapus kategori

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}