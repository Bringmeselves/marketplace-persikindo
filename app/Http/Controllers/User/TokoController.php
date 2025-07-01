<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Chat;
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
       $defaultKeywords = [
                // Kode Pos Kota Bandung (update)
            '40113', '40195', '40191', '40198', '40197', '40193', '40196',
            '40153', '40154', '40151', '40152', '40142', '40141',
            '40121', '40122', '40123', '40124', '40125', '40126', '40127',
            '40128', '40129', '40130', '40131', '40132', '40133',
            '40134', '40135', '40136', '40137', '40138', '40139', '40140',
            '40143', '40144', '40145', '40146', '40147', '40148',
            '40149', '40150', '40155', '40156', '40157', '40158',
            '40159', '40160', '40161', '40162', '40163', '40164', '40165',
            '40166',        

            '40111', // Kota Bandung
            '40311', // Kab. Bandung
            '40551', // Bandung Barat
            '17111', // Bekasi
            '17510', // Kab. Bekasi
            '16111', // Bogor
            '16910', // Kab. Bogor
            '40511', // Cimahi
            '45111', // Cirebon
            '45611', // Kab. Cirebon
            '16411', // Depok
            '44111', // Garut
            '45211', // Indramayu
            '41311', // Karawang
            '45511', // Kuningan
            '45411', // Majalengka
            '46396', // Pangandaran
            '41111', // Purwakarta
            '41211', // Subang
            '43111', // Sukabumi
            '43311', // Kab. Sukabumi
            '45311', // Sumedang
            '46111', // Tasikmalaya
            '46411', // Kab. Tasikmalaya
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

        // Ambil daftar chat dari pembeli untuk toko ini
        $daftarChat = Chat::with(['user', 'pesan'])->where('toko_id', $toko->id)->latest()->get();
        
        return view('user.toko.kelola', compact('toko', 'produkList', 'daftarChat'));
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
