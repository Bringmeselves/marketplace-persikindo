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
    // Menyimpan instance WilayahService untuk ambil data kota (karena provinces dihilangkan)
    protected $wilayahService;

    // Inject WilayahService lewat constructor
    public function __construct(WilayahService $wilayahService)
    {
        $this->wilayahService = $wilayahService;
    }

    // Menampilkan daftar toko milik user yang login
    public function index()
    {
        $user = Auth::user();

        // Cek akses hanya untuk user role 'anggota'
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        // Ambil semua toko milik user tersebut
        $tokoList = Toko::where('user_id', $user->id)->get();

        return view('user.toko.index', compact('tokoList'));
    }

    // Tampilkan form untuk membuat toko baru
    public function create()
    {
        $user = Auth::user();

        // Cegah akses jika bukan anggota
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat toko.');
        }

        // Cegah membuat lebih dari satu toko
        if ($user->toko) {
            return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])
                ->with('error', 'Anda sudah memiliki toko.');
        }

        // Ambil daftar kota saja (provinces dihilangkan)
        $cities = $this->wilayahService->getCities();

        return view('user.toko.create', compact('cities'));
    }

    // Simpan data toko baru ke database
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validasi akses
        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menambahkan toko.');
        }

        // Cegah user menambahkan lebih dari satu toko
        if ($user->toko) {
            return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])
                ->with('error', 'Anda sudah memiliki toko.');
        }

        // Validasi input dari form
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'alamat' => 'nullable|string|max:255',
            'nomer_wa' => 'nullable|string|max:15',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // Validasi hanya untuk 'cities' saja, berdasarkan ID kota yang ada
            'cities' => 'required|integer|in:' . implode(',', array_column($this->wilayahService->getCities(), 'id')),
        ]);

        // Simpan data toko
        $toko = new Toko();
        $toko->user_id = $user->id;
        $toko->nama_toko = $request->input('nama_toko');
        $toko->keterangan = $request->input('keterangan');
        $toko->alamat = $request->input('alamat');
        $toko->nomer_wa = $request->input('nomer_wa');
        $toko->cities = $request->input('cities'); // Simpan ID kota saja

        // Upload foto jika ada
        if ($request->hasFile('foto_toko')) {
            $fotoTokoPath = $request->file('foto_toko')->store('uploads', 'public');
            $toko->foto_toko = $fotoTokoPath;
        }

        // Simpan ke database
        $toko->save();

        return redirect()->route('user.toko.kelola', ['id' => $toko->id])->with('success', 'Toko berhasil ditambahkan.');
    }

    // Tampilkan form edit toko
    public function edit($id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        // Validasi akses user
        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit toko ini.');
        }

        // Ambil data kota saja (provinces dihilangkan)
        $cities = $this->wilayahService->getCities();

        return view('user.toko.edit', compact('toko', 'cities'));
    }

    // Proses update data toko
    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        // Validasi akses
        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit toko ini.');
        }

        // Validasi input dari form
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'alamat' => 'nullable|string|max:255',
            'nomer_wa' => 'nullable|string|max:15',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cities' => 'required|integer|in:' . implode(',', array_column($this->wilayahService->getCities(), 'id')),
        ]);

        // Update data
        $toko->nama_toko = $request->input('nama_toko');
        $toko->keterangan = $request->input('keterangan');
        $toko->alamat = $request->input('alamat');
        $toko->nomer_wa = $request->input('nomer_wa');
        $toko->cities = $request->input('cities'); // Update ID kota

        // Update foto jika ada upload ulang
        if ($request->hasFile('foto_toko')) {
            // Hapus foto lama jika ada
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

        // Pastikan hanya pemilik toko yang bisa mengakses
        $toko = Toko::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        // Ambil daftar produk milik toko
        $produkList = $toko->produk()->get();

        // Ambil nama kota berdasarkan ID kota toko
        $cityName = optional($this->wilayahService->getCityById($toko->cities))['name'] ?? null;

        // Tambahkan properti city_name pada objek toko untuk ditampilkan di view
        $toko->city_name = $cityName;

        return view('user.toko.kelola', compact('toko', 'produkList'));
    }

    // Menampilkan etalase toko ke publik (pembeli)
    public function show($id)
    {
        $toko = Toko::with('produk')->findOrFail($id);

        // Tambahkan city_name jika kamu masih pakai wilayahService
        $toko->city_name = optional($this->wilayahService->getCityById($toko->cities))['name'] ?? null;

        // Ambil produk berdasarkan toko ini
        $produk = $toko->produk()->latest()->paginate(12);

        // Ambil semua kategori untuk dropdown filter (jika ingin digunakan)
        $kategori = \App\Models\Kategori::all();

        return view('user.toko.show', compact('toko', 'produk', 'kategori'));
    }


    // Hapus toko dan file terkait
    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        // Cek akses user
        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus toko ini.');
        }

        // Hapus foto jika ada
        if ($toko->foto_toko && Storage::exists('public/' . $toko->foto_toko)) {
            Storage::delete('public/' . $toko->foto_toko);
        }

        // Soft delete toko
        $toko->delete();

        return redirect()->route('user.toko.create')->with('success', 'Toko berhasil dihapus.');
    }
}
