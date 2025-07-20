<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Checkout;
use App\Models\CheckoutItem;
use Illuminate\Support\Str;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeranjangController extends Controller
{
    /**
     * Menampilkan daftar produk di keranjang pengguna (dari session).
     */
    public function index()
    {
        // Ambil data keranjang dari session, default kosong jika belum ada
        $keranjang = session()->get('keranjang', []);
        
        // Ambil produk_id dari keranjang dan unikkan agar tidak duplikat
        $produkIds = collect($keranjang)->pluck('produk_id')->unique();

        // Ambil data produk beserta relasi varian berdasarkan produk_id yang ada di keranjang
        $produkList = Produk::with('varian')->whereIn('id', $produkIds)->get()->keyBy('id');

        // Tampilkan view dengan data keranjang dan produk
        return view('user.keranjang.index', compact('keranjang', 'produkList'));
    }

    /**
     * Menambahkan produk ke keranjang (disimpan sementara di session).
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'varian_id' => 'nullable|exists:varian,id',
        ]);

        // Cari produk berdasarkan produk_id
        $produk = Produk::findOrFail($request->produk_id);

        // Cek apakah produk milik user sendiri, jika iya tolak penambahan
        if ($produk->user_id == Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa memasukkan produk milikmu sendiri ke keranjang.');
        }

        $jumlah = 1; // Default jumlah produk yang ditambahkan
        $varian_id = $request->varian_id;

        $harga_satuan = $produk->harga; // Harga produk default
        $varian_nama = null;
        $varian_gambar = null;

        // Jika ada varian dipilih, ambil data varian tersebut
        if ($varian_id) {
            $varian = $produk->varian()->where('id', $varian_id)->first();
            if (!$varian) {
                return back()->with('error', 'Varian tidak ditemukan.');
            }

            // Cek stok varian cukup untuk jumlah yang akan ditambahkan
            if ($varian->stok < $jumlah) {
                return back()->with('error', 'Stok varian tidak mencukupi.');
            }

            $varian_nama = $varian->nama;
            $varian_gambar = $varian->gambar;

            // Jika varian punya harga khusus, gunakan harga tersebut
            if ($varian->harga !== null) {
                $harga_satuan = $varian->harga;
            }
        }

        // Hitung total harga berdasarkan harga satuan dan jumlah
        $total_harga = $harga_satuan * $jumlah;

        // Ambil keranjang dari session
        $keranjang = session()->get('keranjang', []);
        
        // Buat key unik berdasarkan produk dan varian (jika ada)
        $key = $produk->id . '-' . ($varian_id ?? 'null');

        // Jika item sudah ada di keranjang, update jumlah dan total harga
        if (isset($keranjang[$key])) {
            $keranjang[$key]['jumlah'] += $jumlah;

            // Jika varian, cek stok apakah mencukupi
            if ($varian_id) {
                $varian = $produk->varian()->where('id', $varian_id)->first();
                if ($keranjang[$key]['jumlah'] > $varian->stok) {
                    return back()->with('error', 'Jumlah melebihi stok varian.');
                }
            }

            // Update total harga
            $keranjang[$key]['total_harga'] = $keranjang[$key]['jumlah'] * $harga_satuan;
        } else {
            // Jika item belum ada di keranjang, buat data baru
            $keranjang[$key] = [
                'produk_id' => $produk->id,
                'nama_produk' => $produk->nama,
                'toko_id' => $produk->toko_id,
                'varian_id' => $varian_id,
                'varian_nama' => $varian_nama,
                'varian_gambar' => $varian_gambar,
                'jumlah' => $jumlah,
                'harga_satuan' => $harga_satuan,
                'total_harga' => $total_harga,
                'gambar' => $produk->gambar,
                'varian_tersedia' => $produk->varian->map(fn ($v) => [
                    'id' => $v->id,
                    'nama' => $v->nama,
                ])->toArray(),
            ];
        }

        // Simpan kembali ke session
        session()->put('keranjang', $keranjang);

        // Redirect ke halaman keranjang dengan pesan sukses
        return redirect()->route('user.keranjang.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update jumlah atau varian item di keranjang.
     */
    public function update(Request $request, $key)
    {
        // Validasi input
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'varian_id' => 'nullable|exists:varian,id',
        ]);

        // Ambil keranjang dari session
        $keranjang = session()->get('keranjang', []);
        if (!isset($keranjang[$key])) {
            return back()->with('error', 'Item tidak ditemukan.');
        }

        $item = $keranjang[$key];
        $produk = Produk::with('varian')->findOrFail($item['produk_id']);

        $harga_satuan = $produk->harga;
        $varian_nama = null;
        $varian_gambar = null;

        // Jika ada varian yang dipilih, validasi dan update harga & detail varian
        if ($request->varian_id) {
            $varian = $produk->varian->where('id', $request->varian_id)->first();
            if (!$varian) {
                return back()->with('error', 'Varian tidak ditemukan.');
            }

            if ($request->jumlah > $varian->stok) {
                return back()->with('error', 'Jumlah melebihi stok varian.');
            }

            $harga_satuan = $varian->harga ?? $produk->harga;
            $varian_nama = $varian->nama;
            $varian_gambar = $varian->gambar;
        }

        $jumlah = $request->jumlah;
        $total_harga = $harga_satuan * $jumlah;

        // Hapus item lama dari keranjang berdasarkan key lama
        unset($keranjang[$key]);
        
        // Buat key baru sesuai dengan varian terbaru
        $newKey = $produk->id . '-' . ($request->varian_id ?? 'null');

        // Masukkan data item terbaru ke keranjang
        $keranjang[$newKey] = [
            'produk_id' => $produk->id,
            'nama_produk' => $produk->nama,
            'toko_id' => $produk->toko_id,
            'varian_id' => $request->varian_id,
            'varian_nama' => $varian_nama,
            'varian_gambar' => $varian_gambar,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'total_harga' => $total_harga,
            'gambar' => $produk->gambar,
        ];

        // Simpan kembali ke session
        session()->put('keranjang', $keranjang);

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Menghapus item dari keranjang session.
     */
    public function destroy($key)
    {
        // Ambil keranjang dari session
        $keranjang = session()->get('keranjang', []);
        
        // Hapus item jika ada berdasarkan key
        if (isset($keranjang[$key])) {
            unset($keranjang[$key]);
            session()->put('keranjang', $keranjang);
        }

        // Redirect kembali dengan pesan sukses
        return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Membuat checkout dari item keranjang tertentu.
     */
    public function checkout(Request $request)
{
    // Validasi input key array
    $request->validate([
        'keys' => 'required|array|min:1',
        'keys.*' => 'required|string',
    ]);

    $keranjang = session()->get('keranjang', []);
    $selectedItems = [];

    foreach ($request->keys as $key) {
        if (!isset($keranjang[$key])) {
            return back()->with('error', 'Beberapa produk tidak ditemukan di keranjang.');
        }
        $selectedItems[] = $keranjang[$key];
    }

    // Pastikan semua produk dari toko yang sama
    $tokoIds = collect($selectedItems)->pluck('toko_id')->unique();
    if ($tokoIds->count() > 1) {
        return back()->with('error', 'Produk yang dipilih harus dari toko yang sama.');
    }

    $toko_id = $tokoIds->first();

    // Cek apakah ada checkout pending untuk toko ini
    $checkout = Checkout::where('user_id', Auth::id())
        ->where('status', 'pending')
        ->where('toko_id', $toko_id)
        ->first();

    if (!$checkout) {
        $checkout = Checkout::create([
            'user_id'       => Auth::id(),
            'toko_id'       => $toko_id,
            'status'        => 'pending',
            'total_harga'   => 0,
            'kode_checkout' => 'CO-' . strtoupper(Str::random(8)),
        ]);
    }

    foreach ($selectedItems as $item) {
        $produk = Produk::with('varian')->findOrFail($item['produk_id']);

        // Cegah user membeli produk sendiri
        if ($produk->user_id == Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa membeli produk milikmu sendiri.');
        }

        // Cek varian jika ada
        $varian = null;
        if ($item['varian_id']) {
            $varian = $produk->varian()->where('id', $item['varian_id'])->first();
            if (!$varian || $varian->stok < $item['jumlah']) {
                return back()->with('error', 'Stok varian tidak mencukupi.');
            }
        }

        // Tambahkan atau update item
        $existingItem = $checkout->item()
            ->where('produk_id', $produk->id)
            ->where('varian_id', $item['varian_id'])
            ->first();

        if ($existingItem) {
            $existingItem->jumlah += $item['jumlah'];
            $existingItem->total_harga = $existingItem->jumlah * $existingItem->harga_satuan;
            $existingItem->save();
        } else {
            $checkout->item()->create([
                'produk_id'    => $produk->id,
                'toko_id'      => $produk->toko_id,
                'varian_id'    => $item['varian_id'],
                'jumlah'       => $item['jumlah'],
                'harga_satuan' => $item['harga_satuan'],
                'total_harga'  => $item['harga_satuan'] * $item['jumlah'],
                'gambar'       => $item['gambar'],
            ]);
        }

        // Hapus dari keranjang
        unset($keranjang[$item['produk_id'] . '-' . ($item['varian_id'] ?? 'null')]);
    }

    // Simpan kembali session keranjang
    session()->put('keranjang', $keranjang);

    // Update total harga
    $checkout->total_harga = $checkout->item->sum('total_harga');
    $checkout->save();

    return redirect()->route('user.checkout.create', $checkout->id)
        ->with('success', 'Produk berhasil ditambahkan ke checkout.');
}

}
