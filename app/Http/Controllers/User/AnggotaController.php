<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function __construct()
    {
        // Cegah admin mengakses halaman ini
        if (Auth::check() && Auth::user()->role === 'admin') {
            abort(403, 'Halaman ini hanya untuk user biasa.');
        }
    }

    /**
     * Tampilkan form pengajuan anggota.
     */
    public function create()
    {
        // Cek apakah user sudah mengajukan sebelumnya
        $existing = Anggota::where('user_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('dashboard')->with('info', 'Anda sudah mengajukan permohonan sebagai anggota.');
        }

        return view('user.anggota.create');
    }

    /**
     * Simpan pengajuan anggota.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap'       => 'required|string|max:255',
            'nik'                => 'required|string|max:20',
            'nama_perusahaan'    => 'required|string|max:255',
            'legalitas'          => 'required|in:CV,PT',
            'nib'                => 'required|string|max:255',
            'npwp'               => 'required|string|max:255',
            'sertifikat_halal'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'pirt'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'bukti_pendaftaran'  => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Simpan file
        $buktiPendaftaranPath = $request->file('bukti_pendaftaran')->store('bukti_pendaftaran', 'public');
        $sertifikatHalalPath = $request->hasFile('sertifikat_halal')
            ? $request->file('sertifikat_halal')->store('sertifikat_halal', 'public')
            : null;
        $pirtPath = $request->hasFile('pirt')
            ? $request->file('pirt')->store('pirt', 'public')
            : null;

        Anggota::create([
            'user_id'             => Auth::id(),
            'status'              => 'pending',
            'tanggal_pengajuan'   => now(),
            'nama_lengkap'        => $request->nama_lengkap,
            'nik'                 => $request->nik,
            'nama_perusahaan'     => $request->nama_perusahaan,
            'legalitas'           => $request->legalitas,
            'nib'                 => $request->nib,
            'npwp'                => $request->npwp,
            'sertifikat_halal'    => $sertifikatHalalPath,
            'pirt'                => $pirtPath,
            'bukti_pendaftaran'   => $buktiPendaftaranPath,
        ]);

        return redirect()->route('dashboard')->with('success', 'Pengajuan keanggotaan berhasil dikirim. Menunggu verifikasi admin.');
    }

    /**
     * Halaman dashboard anggota
     */
    public function dashboard()
    {
        if (!auth()->user()->aktif) {
            return redirect()->route('user.anggota.create')->with('error', 'Anda belum disetujui sebagai anggota.');
        }

        return view('dashboard'); // buat blade ini di resources/views/anggota/dashboard.blade.php
    }
}
