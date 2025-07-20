<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AnggotaController extends Controller
{
    /**
     * Constructor: pastikan hanya admin yang dapat mengakses controller ini.
     */
    public function __construct()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk admin.');
        }
    }

    /**
     * Menampilkan daftar semua anggota.
     */
    public function index()
    {
        $anggota = Anggota::with('user')->latest()->paginate(10);
        return view('admin.anggota.index', compact('anggota'));
    }

    /**
     * Menampilkan daftar anggota yang masih pending.
     */
    public function showPendingAnggota()
    {
        $anggotaPending = Anggota::where('status', 'pending')->get();
        return view('admin.anggota.pending', compact('anggotaPending'));
    }

    /**
     * Menampilkan detail pengajuan anggota.
     */
    public function show($id)
    {
        $anggota = Anggota::with('user')->findOrFail($id);
        return view('admin.anggota.show', compact('anggota'));
    }

    /**
     * Memverifikasi anggota.
     */
    public function verify($id)
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->status !== 'pending') {
            return redirect()->back()->with('error', 'Anggota ini sudah diverifikasi atau ditolak.');
        }

        // Update status anggota
        $anggota->update([
            'status' => 'approved',
            'tanggal_disetujui' => now(),
        ]);

        // Ubah role user menjadi anggota
        $user = $anggota->user;
        $user->approved = true; // Set approved menjadi true
        $user->role = 'anggota'; // Ubah role menjadi anggota
        $user->save();

        // Perbarui session pengguna
        Auth::setUser($user);

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil diverifikasi.');
    }

    /**
     * Menolak pengajuan anggota.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $anggota = Anggota::findOrFail($id);

        if ($anggota->status !== 'pending') {
            return redirect()->back()->with('error', 'Anggota ini sudah diverifikasi atau ditolak.');
        }

        $anggota->update([
            'status' => 'rejected',
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('admin.anggota.index')->with('success', 'Pendaftaran anggota berhasil ditolak.');
    }

    /**
     * Menghapus data anggota dan file bukti jika ada.
     */
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);

        if ($anggota->bukti_pendaftaran && Storage::disk('public')->exists($anggota->bukti_pendaftaran)) {
            Storage::disk('public')->delete($anggota->bukti_pendaftaran);
        }

        $user = $anggota->user;
        if ($user && $user->role === 'anggota') {
            $user->role = 'user'; // Kembalikan role menjadi user jika anggota dihapus
            $user->approved = false; // Set approved menjadi false
            $user->save();
        }

        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Anggota berhasil dihapus!');
    }

    /**
     * Dashboard anggota (jika user disetujui).
     */
    public function dashboard()
    {
        if (!auth()->user()->approved) {
            return redirect()->route('user.anggota.create')->with('error', 'Anda belum disetujui sebagai anggota.');
        }

        return view('dashboard');
    }
}