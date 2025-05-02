<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Registrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrasiController extends Controller
{
    // Menampilkan form registrasi
    public function create()
    {
        return view('user.registrasi.create');
    }

    // Menyimpan registrasi
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'bukti_pendaftaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('bukti_pendaftaran')) {
            $path = $request->file('bukti_pendaftaran')->store('bukti_pendaftaran', 'public');
        }

        Registrasi::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => $request->nama_lengkap,
            'no_hp' => $request->no_hp,
            'bukti_pendaftaran' => $path,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Registrasi berhasil dikirim.');
    }
}
