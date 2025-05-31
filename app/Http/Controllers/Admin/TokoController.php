<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    // Menampilkan daftar semua toko
    public function index()
    {
        $toko = Toko::with('user.anggota')->get();
        return view('admin.toko.index', compact('toko'));
    }

    // Menghapus toko
    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);
        $toko->delete();

        return redirect()->route('admin.toko.index')->with('success', 'Toko berhasil dihapus.');
    }
}
