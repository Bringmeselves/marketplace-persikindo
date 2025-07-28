<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Toko;
use App\Models\Transaksi;
use App\Models\CheckoutItem;
use App\Models\PenilaianToko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Kategori;

class TokoController extends Controller
{
    // Ambil daftar kota dari API Komerce dengan cache
    private function fetchOrigins()
    {
        // Gunakan cache selama 12 jam (720 menit)
        return Cache::remember('komerce_origins_cache', now()->addHours(12), function () {
            $defaultKeywords = [
                // Kode Pos Bandung dan kota lain (seperti yang kamu punya)
                '40113', '40195', '40191', '40198', '40197', '40193', '40196',
                '40153', '40154', '40151', '40152', '40142', '40141',
                '40121', '40122', '40123', '40124', '40125', '40126', '40127',
                '40128', '40129', '40130', '40131', '40132', '40133',
                '40134', '40135', '40136', '40137', '40138', '40139', '40140',
                '40143', '40144', '40145', '40146', '40147', '40148',
                '40149', '40150', '40155', '40156', '40157', '40158',
                '40159', '40160', '40161', '40162', '40163', '40164', '40165',
                '40166',        
                '40111', '40311', '40551', '17111', '17510', '16111', '16910',
                '40511', '45111', '45611', '16411', '44111', '45211', '41311',
                '45511', '45411', '46396', '41111', '41211', '43111', '43311',
                '45311', '46111', '46411',
            ];

            $allCities = [];

            foreach ($defaultKeywords as $kw) {
                $response = Http::withHeaders([
                    'x-api-key' => env('KOMERCE_API_KEY'),
                    'Accept' => 'application/json',
                ])->timeout(10)->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                    'keyword' => $kw,
                ]);

                if ($response->ok() && isset($response['data'])) {
                    $allCities = array_merge($allCities, $response['data']);
                }
            }

            return collect($allCities)->unique('id')->values()->all();
        });
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

        $toko->city_name = $this->getCityNameById($toko->origin);
        $daftarChat = Chat::with(['user', 'pesan'])->where('toko_id', $toko->id)->latest()->get();

        // Transaksi yang belum diisi resi
        $transaksiMasuk = Transaksi::with([
        'checkout.item.produk',
        'checkout.item.varian',
        'pengiriman',
        'pembayaran',
        'user'
        ])
        ->where('status', 'diproses')
        ->whereNull('resi')
        ->whereHas('checkout.item.produk', function ($q) use ($toko) {
            $q->where('toko_id', $toko->id);
        })
        ->latest()
        ->paginate(3);

        // menampilkan riwayat transaksi (yang sudah dikirim/selesai/dibatalkan)
        $riwayatTransaksi = Transaksi::with(['checkout.item.produk', 'pengiriman', 'pembayaran', 'user'])
            ->whereIn('status', ['dikirim', 'selesai', 'dibatalkan']) // status selain diproses
            ->whereHas('produk', function ($q) use ($toko) {
                $q->where('toko_id', $toko->id);
            })
            ->latest()
            ->get();

        // Total seluruh produk yang berhasil terjual dari toko ini
        $totalProdukTerjual = CheckoutItem::whereHas('checkout.transaksi', function ($query) {
                $query->where('status', 'selesai'); // hanya ambil transaksi yang sudah selesai
            })->whereHas('produk', function ($query) use ($toko) {
                $query->where('toko_id', $toko->id); // filter berdasarkan toko saat ini
            })->sum('jumlah'); // total jumlah item yang terjual

        // Jumlah transaksi yang berstatus 'selesai' pada toko ini
        $jumlahTransaksiSelesai = Transaksi::where('status', 'selesai')
            ->whereHas('checkout.item.produk', function ($query) use ($toko) {
                $query->where('toko_id', $toko->id); // hanya transaksi dari produk milik toko ini
            })->count(); // hitung jumlah transaksi

        // Total pendapatan (jumlah pembayaran) dari semua transaksi yang sudah selesai
        $saldo = $toko->saldo;

        // Jumlah produk aktif yang dimiliki oleh toko ini
        $jumlahProduk = $produkList->count(); // produk sudah di-load dari relasi $toko->produk()

        // Ambil 5 transaksi terakhir yang sudah selesai untuk ditampilkan di riwayat ringkas
        $transaksiTerakhir = Transaksi::where('status', 'selesai')
            ->whereHas('checkout.item.produk', function ($query) use ($toko) {
                $query->where('toko_id', $toko->id);
            })->latest() // urut dari yang terbaru
            ->take(5) // ambil 5 transaksi terakhir
            ->get(); // ambil datanya
        
        return view('user.toko.kelola', compact(
            'toko',
            'produkList',
            'daftarChat',
            'transaksiMasuk',
            'riwayatTransaksi',
            'totalProdukTerjual',
            'jumlahTransaksiSelesai',
            'saldo',
            'jumlahProduk',
            'transaksiTerakhir'
        ));
    }

    // Halaman publik toko
    public function show($id)
    {
        $toko = Toko::with([
            'produk',
            'penilaian.user' // ambil juga penilaian + user yang memberi nilai
        ])->findOrFail($id);

        $produk = $toko->produk()
            ->withCount(['transaksi as jumlah_terjual' => function ($q) {
                $q->where('status', 'selesai');
            }])
            ->latest()
            ->paginate(12);

        $kategori = Kategori::all();

        // Tambahkan nama kota
        $toko->city_name = $this->getCityNameById($toko->origin);

        // Cek apakah user sudah memberi penilaian toko
        $sudahNilaiToko = PenilaianToko::where('toko_id', $toko->id)
            ->where('user_id', auth()->id())
            ->exists();

        // Hitung rata-rata rating toko
        $totalReview = $toko->penilaian->count();
        $avgRating = $toko->penilaian->avg('rating') ?? 0;

        return view('user.toko.show', compact('toko', 'produk', 'kategori', 'sudahNilaiToko', 'totalReview', 'avgRating'));
    }

    public function reviews($id)
    {
        $toko = Toko::with(['penilaian.user'])->findOrFail($id);

        $reviews = $toko->penilaian()->latest()->paginate(10);
        $totalReview = $toko->penilaian()->count();

        return view('user.penilaian-toko.reviews', compact('toko', 'reviews', 'totalReview'));
    }

    /**
     * Menampilkan semua transaksi dari produk-produk milik toko ini.
     */
    public function riwayatTransaksi()
    {
        $user = Auth::user();

        // Ambil toko milik user
        $toko = Toko::where('user_id', $user->id)->first();

        if (!$toko) {
            return redirect()->route('dashboard')->with('error', 'Toko tidak ditemukan.');
        }

        // Ambil transaksi yang produk-nya milik toko ini
        $transaksiList = Transaksi::with([
                'checkout',
                'checkout.user',
                'checkout.item',
                'checkout.item.produk',
                'checkout.item.varian',
                'checkout.pengiriman',
                'checkout.pembayaran',
                'pengiriman',
                'pembayaran',
            ])
            ->whereHas('produk', function ($query) use ($toko) {
                $query->where('toko_id', $toko->id);
            })
            ->latest()
            ->paginate(5);

        return view('user.toko.riwayat', compact('transaksiList', 'toko'));
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
