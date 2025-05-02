<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AnggotaController extends Controller
{
    /**
     * Menampilkan form pendaftaran anggota untuk user.
     */
    public function create()
    {
        $user = Auth::user();

        // Cek apakah user sudah menjadi anggota
        if ($user->anggota) {
            return redirect()->back()->with('error', 'Anda sudah terdaftar sebagai anggota.');
        }

        return view('user.anggota.create');
    }

    /**
     * Menyimpan data pendaftaran anggota yang diajukan oleh user.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Cek apakah user sudah pernah mendaftar sebagai anggota
        if ($user->anggota) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan pendaftaran anggota.');
        }

        // Validasi input
        $request->validate([
            'bukti_pendaftaran' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'nama_perusahaan' => 'required|string|max:255',
            'legalitas' => 'nullable|string|max:255',
            'nib' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'sertifikat_halal' => 'nullable|string|max:255',
            'pirt' => 'nullable|string|max:255',
        ]);

        // Upload file bukti pendaftaran
        $path = $request->file('bukti_pendaftaran')->store('bukti_pendaftaran', 'public');

        // Simpan data ke database
        Anggota::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'bukti_pendaftaran' => $path,
            'tanggal_pengajuan' => now(),
            'nama_perusahaan' => $request->nama_perusahaan,
            'legalitas' => $request->legalitas,
            'nib' => $request->nib,
            'npwp' => $request->npwp,
            'sertifikat_halal' => $request->sertifikat_halal,
            'pirt' => $request->pirt,
        ]);

        // Tambahkan role "anggota" jika belum dimiliki
        if (!$user->hasRole('anggota')) {
            $user->assignRole('anggota');
        }

        return redirect()->route('user.anggota.create')->with('success', 'Pendaftaran anggota berhasil diajukan!');
    }
}