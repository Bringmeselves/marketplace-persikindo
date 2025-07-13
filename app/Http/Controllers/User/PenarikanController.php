<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penarikan;
use Illuminate\Support\Facades\Auth;

class PenarikanController extends Controller
{
    public function index()
    {
        $penarikan = Penarikan::where('toko_id', Auth::user()->toko->id)->latest()->get();
        return view('user.penarikan.index', compact('penarikan'));
    }

    public function create()
    {
        return view('user.penarikan.create');
    }

    public function store(Request $request)
    {
        $minimalPenarikan = 50000;

        $request->validate([
            'jumlah' => ['required', 'numeric', 'min:' . $minimalPenarikan],
            'rekening_tujuan' => 'required|string',
        ]);

        $toko = Auth::user()->toko;

        // Cek apakah saldo mencukupi
        if ($toko->saldo < $request->jumlah) {
            return back()->withErrors([
                'jumlah' => 'Saldo tidak mencukupi untuk penarikan sebesar Rp ' . number_format($request->jumlah, 0, ',', '.')
            ])->withInput();
        }

        // Buat penarikan
        Penarikan::create([
            'toko_id' => $toko->id,
            'jumlah' => $request->jumlah,
            'rekening_tujuan' => $request->rekening_tujuan,
            'status' => 'pending',
        ]);

        // Optional: bisa langsung kurangi saldo toko (jika model bisnis kamu pakai sistem hold saldo)
        $toko->decrement('saldo', $request->jumlah);

        return redirect()->route('user.penarikan.index')->with('success', 'Permintaan penarikan dikirim');
    }
}
