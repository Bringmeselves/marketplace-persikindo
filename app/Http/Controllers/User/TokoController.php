<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Kategori;

class TokoController extends Controller
{
    // Ambil daftar kota (origin) dari API Komerce
    private function fetchOrigins()
    {
        // Keyword default yang akan digunakan untuk pencarian kota
        $defaultKeywords = [
            'bandung', 'bandung_barat', 'bekasi', 'kabupaten_bekasi',
            'bogor', 'kabupaten_bogor', 'cimahi', 'cirebon', 'kabupaten_cirebon',
            'depok', 'garut', 'indramayu', 'karawang', 'kuningan',
            'majalengka', 'pangandaran', 'purwakarta', 'subang', 'sukabumi',
            'kabupaten_sukabumi', 'sumedang', 'tasikmalaya', 'kabupaten_tasikmalaya'
        ];

        $allCities = [];

        // Looping untuk mengambil data kota berdasarkan keyword
        foreach ($defaultKeywords as $kw) {
            $response = Http::withHeaders([
                'x-api-key' => env('KOMERCE_API_KEY'),
                'Accept' => 'application/json',
            ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                'keyword' => $kw,
            ]);

            // Jika respon sukses dan memiliki data, gabungkan datanya
            if ($response->ok() && isset($response['data'])) {
                $allCities = array_merge($allCities, $response['data']);
            }
        }

        // Kembalikan data unik berdasarkan 'id'
        return collect($allCities)->unique('id')->values()->all();
    }

    // Ambil nama kota berdasarkan ID kota
    private function getCityNameById($id)
    {
        $origin = $this->fetchOrigins();
        $city = collect($origin)->firstWhere('id', $id);
        return $city['label'] ?? null;
    }

    // Tampilkan daftar toko milik user (anggota)
    public function index()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }

        $tokoList = Toko::where('user_id', $user->id)->get();
        return view('user.toko.index', compact('tokoList'));
    }

    // Formulir pembuatan toko
    public function create()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk membuat toko.');
        }

        // Cek jika user sudah memiliki toko
        if ($user->toko) {
            return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])
                ->with('error', 'Anda sudah memiliki toko.');
        }

        $origin = $this->fetchOrigins();
        return view('user.toko.create', compact('origin'));
    }

    // Simpan data toko ke database
    public function store(Request $request)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menambahkan toko.');
        }

        // Cek jika sudah memiliki toko
        if ($user->toko) {
            return redirect()->route('user.toko.kelola', ['id' => $user->toko->id])
                ->with('error', 'Anda sudah memiliki toko.');
        }

        // Ambil semua ID origin yang valid
        $originIds = array_column($this->fetchOrigins(), 'id');

        // Validasi input
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'alamat' => 'nullable|string|max:255',
            'nomer_wa' => 'nullable|string|max:15',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'origin' => 'required|integer|in:' . implode(',', $originIds),
        ]);

        // Simpan toko baru
        $toko = new Toko();
        $toko->user_id = $user->id;
        $toko->nama_toko = $request->nama_toko;
        $toko->keterangan = $request->keterangan;
        $toko->alamat = $request->alamat;
        $toko->nomer_wa = $request->nomer_wa;
        $toko->origin = $request->origin;

        // Simpan foto jika ada
        if ($request->hasFile('foto_toko')) {
            $toko->foto_toko = $request->file('foto_toko')->store('uploads', 'public');
        }

        $toko->save();

        return redirect()->route('user.toko.kelola', ['id' => $toko->id])->with('success', 'Toko berhasil ditambahkan.');
    }

    // Form edit toko
    public function edit($id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit toko ini.');
        }

        $origin = $this->fetchOrigins();

        return view('user.toko.edit', compact('toko', 'origin'));
    }

    // Update data toko
    public function update(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk mengedit toko ini.');
        }

        $originIds = array_column($this->fetchOrigins(), 'id');

        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',
            'alamat' => 'nullable|string|max:255',
            'nomer_wa' => 'nullable|string|max:15',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'origin' => 'required|integer|in:' . implode(',', $originIds),
        ]);

        // Update data toko
        $toko->nama_toko = $request->nama_toko;
        $toko->keterangan = $request->keterangan;
        $toko->alamat = $request->alamat;
        $toko->nomer_wa = $request->nomer_wa;
        $toko->origin = $request->origin;

        // Hapus foto lama jika ada, dan simpan yang baru
        if ($request->hasFile('foto_toko')) {
            if ($toko->foto_toko && Storage::exists('public/' . $toko->foto_toko)) {
                Storage::delete('public/' . $toko->foto_toko);
            }
            $toko->foto_toko = $request->file('foto_toko')->store('uploads', 'public');
        }

        $toko->save();

        return redirect()->route('user.toko.kelola', ['id' => $toko->id])->with('success', 'Toko berhasil diperbarui.');
    }

    // Halaman kelola toko (khusus pemilik)
    public function kelola($id)
    {
        $user = Auth::user();
        $toko = Toko::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $produkList = $toko->produk()->get();

        // Tambahkan nama kota
        $toko->city_name = $this->getCityNameById($toko->origin);

        return view('user.toko.kelola', compact('toko', 'produkList'));
    }

    // Halaman publik toko
    public function show($id)
    {
        $toko = Toko::with('produk')->findOrFail($id);
        $produk = $toko->produk()->latest()->paginate(12);
        $kategori = Kategori::all();

        // Tambahkan nama kota
        $toko->city_name = $this->getCityNameById($toko->origin);

        return view('user.toko.show', compact('toko', 'produk', 'kategori'));
    }

    // Hapus toko
    public function destroy($id)
    {
        $toko = Toko::findOrFail($id);
        $user = Auth::user();

        if ($toko->user_id !== $user->id || $user->role !== 'anggota') {
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses untuk menghapus toko ini.');
        }

        // Hapus foto jika ada
        if ($toko->foto_toko && Storage::exists('public/' . $toko->foto_toko)) {
            Storage::delete('public/' . $toko->foto_toko);
        }

        $toko->delete();

        return redirect()->route('user.toko.create')->with('success', 'Toko berhasil dihapus.');
    }
}
