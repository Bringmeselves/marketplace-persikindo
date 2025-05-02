<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar anggota yang sudah mendaftar.
     */
    public function index()
    {
        $anggota = Anggota::with('user')->latest()->paginate(10);
        return view('admin.anggota.index', compact('anggota'));
    }

    /**
     * Menampilkan form untuk menambahkan anggota baru oleh admin.
     */
    public function create()
    {
        // Ambil user yang belum menjadi anggota
        $users = User::doesntHave('anggota')->get();
        return view('admin.anggota.create', compact('users'));
    }

    /**
     * Menyimpan anggota baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,approved,rejected',
            'bukti_pendaftaran' => 'required|file|mimes:pdf,jpg,jpeg,png',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_disetujui' => 'nullable|date',
            'nama_perusahaan' => 'required|string|max:255',
            'legalitas' => 'nullable|string|max:255',
            'nib' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'sertifikat_halal' => 'nullable|string|max:255',
            'pirt' => 'nullable|string|max:255',
        ]);

        // Cegah duplikasi anggota pada user
        if (Anggota::where('user_id', $request->user_id)->exists()) {
            return back()->withErrors(['user_id' => 'User ini sudah terdaftar sebagai anggota.'])->withInput();
        }

        // Upload file
        $path = $request->file('bukti_pendaftaran')->store('bukti_pendaftaran', 'public');

        // Simpan data anggota
        Anggota::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'bukti_pendaftaran' => $path,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'tanggal_disetujui' => $request->tanggal_disetujui,
            'nama_perusahaan' => $request->nama_perusahaan,
            'legalitas' => $request->legalitas,
            'nib' => $request->nib,
            'npwp' => $request->npwp,
            'sertifikat_halal' => $request->sertifikat_halal,
            'pirt' => $request->pirt,
        ]);

        // Beri role 'anggota' ke user (pastikan model User pakai HasRoles)
        $user = User::find($request->user_id);
        if (!$user->hasRole('anggota')) {
            $user->assignRole('anggota');
        }

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    /**
     * Menghapus data anggota.
     */
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        // Hapus file jika ada
        if ($anggota->bukti_pendaftaran && Storage::disk('public')->exists($anggota->bukti_pendaftaran)) {
            Storage::disk('public')->delete($anggota->bukti_pendaftaran);
        }

        // Cabut role 'anggota' dari user saat data dihapus
        $user = $anggota->user;
        if ($user && $user->hasRole('anggota')) {
            $user->removeRole('anggota');
        }

        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus!');
    }
}