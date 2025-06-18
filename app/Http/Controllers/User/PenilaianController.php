<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penilaian;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Simpan penilaian produk
     */
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:1000',
        ]);

        Penilaian::create([
            'produk_id' => $request->produk_id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'ulasan' => $request->ulasan,
        ]);

        return redirect()->back()->with('success', 'Penilaian berhasil dikirim.');
    }
}
