<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PenilaianToko;
use App\Models\Toko;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;

class PenilaianTokoController extends Controller
{
    public function create(Toko $toko)
    {
        $userId = Auth::id();

        // Cek apakah user pernah membeli dari toko ini
        $pernahBeli = Transaksi::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereHas('checkout.item.produk', function ($q) use ($toko) {
                $q->where('toko_id', $toko->id);
            })
            ->exists();

        if (!$pernahBeli) {
            return back()->with('error', 'Anda belum pernah membeli dari toko ini.');
        }

        // Cek apakah sudah beri penilaian
        $sudahNilai = PenilaianToko::where('toko_id', $toko->id)
            ->where('user_id', $userId)
            ->exists();

        if ($sudahNilai) {
            return redirect()->route('user.transaksi.index')->with('info', 'Anda sudah memberi penilaian untuk toko ini.');
        }

        return view('user.penilaian-toko.create', compact('toko'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'toko_id' => 'required|exists:toko,id',
            'rating'  => 'required|integer|min:1|max:5',
            'ulasan'  => 'nullable|string|max:1000',
        ]);

        $tokoId = $request->toko_id;

        $pernahBeli = Transaksi::where('user_id', $userId)
            ->where('status', 'selesai')
            ->whereHas('checkout.item.produk', function ($q) use ($tokoId) {
                $q->where('toko_id', $tokoId);
            })
            ->exists();

        if (!$pernahBeli) {
            return back()->with('error', 'Anda belum menyelesaikan transaksi dengan toko ini.');
        }

        $sudahNilai = PenilaianToko::where('toko_id', $tokoId)
            ->where('user_id', $userId)
            ->exists();

        if ($sudahNilai) {
            return redirect()->route('user.transaksi.index')->with('info', 'Penilaian untuk toko sudah ada.');
        }

        PenilaianToko::create([
            'toko_id' => $tokoId,
            'user_id' => $userId,
            'rating'  => $request->rating,
            'ulasan'  => $request->ulasan,
        ]);

        return redirect()->route('user.transaksi.index')->with('success', 'Penilaian untuk toko berhasil disimpan.');
    }
}
