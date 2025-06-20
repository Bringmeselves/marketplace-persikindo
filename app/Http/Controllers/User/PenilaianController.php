<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Varian;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function create(Produk $produk)
    {
        $userId = Auth::id();

        // Ambil transaksi terakhir user terhadap produk tersebut
        $transaksi = Transaksi::where('user_id', $userId)
            ->where('produk_id', $produk->id)
            ->latest()
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Anda belum pernah membeli produk ini.');
        }

        // Cek apakah sudah pernah menilai
        $sudahNilai = Penilaian::where('produk_id', $produk->id)
            ->where('user_id', $userId)
            ->first();

        if ($sudahNilai) {
            return redirect()->route('user.transaksi.index')->with('info', 'Anda sudah memberi penilaian untuk produk ini.');
        }

        // Ambil varian jika ada
        $varian = null;
        if ($transaksi->varian_id) {
            $varian = Varian::find($transaksi->varian_id);
        }

        return view('user.penilaian.create', compact('produk', 'varian'));
    }

    public function store(Request $request)
{
    $userId = Auth::id();

    $request->validate([
        'produk_id' => 'required|exists:produk,id',
        'rating' => 'required|integer|min:1|max:5',
        'ulasan' => 'nullable|string|max:1000',
    ]);

    $produkId = $request->produk_id;

    // Cek apakah user pernah beli produk ini
    $pernahBeli = Transaksi::where('user_id', $userId)
        ->where('produk_id', $produkId)
        ->exists();

    if (!$pernahBeli) {
        return back()->with('error', 'Anda belum membeli produk ini.');
    }

    // Cek apakah user sudah beri penilaian
    $sudahNilai = Penilaian::where('produk_id', $produkId)
        ->where('user_id', $userId)
        ->first();

    if ($sudahNilai) {
        return redirect()->route('user.transaksi.index')->with('info', 'Penilaian sudah ada.');
    }

    Penilaian::create([
        'produk_id' => $produkId,
        'user_id'   => $userId,
        'rating'    => $request->rating,
        'ulasan'    => $request->ulasan,
    ]);

    return redirect()->route('user.transaksi.index')->with('success', 'Penilaian berhasil disimpan.');
}
public function destroy($id)
{
    $penilaian = Penilaian::where('id', $id)
        ->where('user_id', Auth::id()) // hanya user yang membuat penilaian yang bisa hapus
        ->firstOrFail();

    $penilaian->delete();

    return back()->with('success', 'Penilaian berhasil dihapus.');
}

}
