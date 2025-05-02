<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registrasi;
use Illuminate\Http\Request;

class RegistrasiController extends Controller
{
    // Menampilkan semua registrasi
    public function index()
    {
        $registrasi = Registrasi::with('user')->latest()->get();
        return view('admin.registrasi.index', compact('registrasi'));
    }

    // Menampilkan detail registrasi
    public function show($id)
    {
        $registrasi = Registrasi::with('user')->findOrFail($id);
        return view('admin.registrasi.show', compact('registrasi'));
    }

    // Update status registrasi
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $registrasi = Registrasi::findOrFail($id);
        $registrasi->status = $request->status;

        if ($request->status === 'disetujui' && !$registrasi->no_anggota) {
            $registrasi->no_anggota = 'ANG-' . str_pad($registrasi->id, 5, '0', STR_PAD_LEFT);
        }

        $registrasi->save();

        return redirect()->back()->with('success', 'Status registrasi diperbarui.');
    }
}
