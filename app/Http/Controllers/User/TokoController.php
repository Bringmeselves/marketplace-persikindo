<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\WilayahService;

class TokoController extends Controller
{
    // Service untuk mengambil data wilayah (kota)
    protected $wilayahService;

    public function __construct(WilayahService $wilayahService)
    {
        $this->wilayahService = $wilayahService;
    }

    // Menampilkan daftar toko milik user login
    public function index()
    {
        $user = Auth::user();

        // Hanya role anggota yang bisa akses
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil toko berdasarkan user
        $tokoList = Toko::where('user_id', $user->id)->get();

        return view('user.toko.index', compact('tokoList'));
    }

    // Form tambah toko
    public function create()
    {
        $user = Auth::user();

        // Cek role
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat toko.');
        }

        // Cek jika sudah punya toko
        if ($user->toko) {
            return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])
                ->with('error', 'Anda sudah memiliki toko.');
        }

        // Ambil daftar kota untuk dropdown origin
        $origins = $this->wilayahService->getOriginList();

        return view('user.toko.create', compact('origins'));
    }

    // Proses simpan toko baru
    public function store(Request $request)
    {
        $user = Auth::user();

        // Cek role
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menambahkan toko.');
        }

        // Cek jika sudah punya toko
        if ($user->toko) {
            return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])
                ->with('error', 'Anda sudah memiliki toko.');
        }

        // Validasi input form
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'alamat' => 'nullable|string|max:255',
            'nomer_wa' => 'nullable|string|max:15',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'origin' => 'required|integer|in:' . implode(',', array_column($this->wilayahService->getOriginList(), 'id')), // Validasi ID kota
        ]);

        // Simpan data ke model
        $toko = new Toko();
        $toko->user_id = $user->id;
        $toko->nama_toko = $request->input('nama_toko');
        $toko->keterangan = $request->input('keterangan');
        $toko->alamat = $request->input('alamat');
        $toko->nomer_wa = $request->input('nomer_wa');
        $toko->origin = $request->input('origin'); // Simpan ID kota

        // Upload foto toko
        if ($request->hasFile('foto_toko')) {
            $fotoTokoPath = $request->file('foto_toko')->store('uploads', 'public');
            $toko->foto_toko = $fotoTokoPath;
        }

        $toko->save();

        return redirect()->route('user.toko.kelola', ['id' => $toko->id])->with('success', 'Toko berhasil ditambahkan.');
    }

    // Form edit toko
    public function edit($id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        // Validasi akses
        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit toko ini.');
        }

        // Ambil daftar kota untuk dropdown origin
        $origins = $this->wilayahService->getOriginList();

        return view('user.toko.edit', compact('toko', 'origins'));
    }

    // Proses update toko
    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        // Validasi akses
        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit toko ini.');
        }

        // Validasi input form
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'alamat' => 'nullable|string|max:255',
            'nomer_wa' => 'nullable|string|max:15',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'origin' => 'required|integer|in:' . implode(',', array_column($this->wilayahService->getOriginList(), 'id')), // Validasi ID kota
        ]);

        // Update data toko
        $toko->nama_toko = $request->input('nama_toko');
        $toko->keterangan = $request->input('keterangan');
        $toko->alamat = $request->input('alamat');
        $toko->nomer_wa = $request->input('nomer_wa');
        $toko->origin = $request->input('origin'); // Simpan perubahan origin

        // Update foto toko
        if ($request->hasFile('foto_toko')) {
            if ($toko->foto_toko && Storage::exists('public/' . $toko->foto_toko)) {
                Storage::delete('public/' . $toko->foto_toko);
            }
            $fotoTokoPath = $request->file('foto_toko')->store('uploads', 'public');
            $toko->foto_toko = $fotoTokoPath;
        }

        $toko->save();

        return redirect()->route('user.toko.kelola', ['id' => $toko->id])->with('success', 'Toko berhasil diperbarui.');
    }

    // Halaman kelola toko dan produk
    public function kelola($id)
    {
        $user = Auth::user();

        // Ambil toko milik user
        $toko = Toko::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Ambil produk dalam toko
        $produkList = $toko->produk()->get();

        // Ambil nama kota dari origin
        $cityName = optional($this->wilayahService->getOriginById($toko->origin))['name'] ?? null;
        $toko->city_name = $cityName;

        return view('user.toko.kelola', compact('toko', 'produkList'));
    }

    // Menampilkan etalase publik toko
    public function show($id)
    {
        $toko = Toko::with('produk')->findOrFail($id);

        // Ambil nama kota dari origin
        $toko->city_name = optional($this->wilayahService->getCityById($toko->origin))['name'] ?? null;

        // Ambil produk dan kategori
        $produk = $toko->produk()->latest()->paginate(12);
        $kategori = \App\Models\Kategori::all();

        return view('user.toko.show', compact('toko', 'produk', 'kategori'));
    }

    // Hapus toko dan foto
    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        // Cek akses
        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus toko ini.');
        }

        // Hapus foto dari storage
        if ($toko->foto_toko && Storage::exists('public/' . $toko->foto_toko)) {
            Storage::delete('public/' . $toko->foto_toko);
        }

        // Soft delete toko
        $toko->delete();

        return redirect()->route('user.toko.create')->with('success', 'Toko berhasil dihapus.');
    }
}
