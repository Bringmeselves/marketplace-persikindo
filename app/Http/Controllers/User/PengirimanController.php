<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Pengiriman;
use App\Models\Produk; // Jangan lupa import model Produk
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PengirimanController extends Controller
{
    /**
     * Simpan data checkout dan pengiriman, kurangi stok produk,
     * lalu redirect ke halaman pembayaran.
     */
    public function store(Request $request)
    {
        // Ambil data checkout yang sudah disimpan di session
        $checkoutData = Session::get('checkout');

        // Jika data checkout tidak ada di session, redirect ke dashboard dengan pesan error
        if (!$checkoutData) {
            return redirect()->route('dashboard')->with('error', 'Data checkout tidak ditemukan.');
        }

        // Ambil data produk dari database berdasar produk_id di session
        $produk = Produk::findOrFail($checkoutData['produk_id']);
        $jumlah = $checkoutData['jumlah'];

        // Cek stok produk, jika stok kurang dari jumlah yang dibeli, batalkan dan kembalikan pesan error
        if ($produk->stok < $jumlah) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        // Validasi input pengiriman dari form
        $request->validate([
            'nama_lengkap'    => 'required|string|max:255',
            'nomor_wa'        => 'required|string|max:20',
            'alamat_penerima' => 'required|string|max:100',
            'provinsi'        => 'nullable|string|max:100',
            'cities'          => 'nullable|string|max:100',
            'kode_pos'        => 'nullable|string|max:10',
            'catatan'         => 'nullable|string',
            'kurir'           => 'nullable|string|max:50',
            'layanan'         => 'nullable|string|max:50',
            'ongkir'          => 'nullable|integer',
        ]);

        // Hitung total harga pembelian (harga produk x jumlah)
        $totalHarga = $jumlah * $produk->harga;

        // Cari data checkout yang statusnya 'pending' untuk user, produk, dan toko tersebut
        // Jika tidak ada, buat data baru (belum disimpan)
        $checkout = Checkout::firstOrNew([
            'user_id'   => Auth::id(),
            'produk_id' => $produk->id,
            'toko_id'   => $produk->toko_id,
            'status'    => 'pending',
        ]);

        // Tandai apakah data checkout ini baru (belum ada di database)
        $isBaru = !$checkout->exists;

        // Isi atau update field jumlah dan total harga pada checkout
        $checkout->fill([
            'jumlah'      => $jumlah,
            'total_harga' => $totalHarga,
        ])->save();

        // Cari atau buat data pengiriman berdasarkan checkout_id
        $pengiriman = Pengiriman::firstOrNew(['checkout_id' => $checkout->id]);

        // Isi data pengiriman dengan data dari form dan properti lain
        $pengiriman->fill([
            'user_id'          => Auth::id(),
            'produk_id'        => $produk->id,
            'toko_id'          => $produk->toko_id,
            'nama_lengkap'     => $request->nama_lengkap,
            'nomor_wa'         => $request->nomor_wa,
            'alamat_penerima'  => $request->alamat_penerima,
            'provinsi'         => $request->provinsi,
            'cities'           => $request->cities,
            'kode_pos'         => $request->kode_pos,
            'catatan'          => $request->catatan,
            'kurir'            => $request->kurir,
            'layanan'          => $request->layanan,
            'ongkir'           => $request->ongkir,
            'status_pengiriman'=> 'belum_dikirim', // Status awal pengiriman belum dikirim
        ])->save();

        // Redirect ke halaman pembayaran dengan membawa id checkout dan pesan sukses
        return redirect()->route('user.pembayaran.create', ['checkout' => $checkout->id])
            ->with('success', 'Checkout dan pengiriman berhasil disimpan. Silakan lanjut ke pembayaran.');
    }
}
