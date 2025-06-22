<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    /**
     * Form tambah alamat pengiriman
     */
    public function alamatCreate($checkoutId)
    {
        $checkout = Checkout::where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.pengiriman.form', compact('checkout'));
    }

    /**
     * Form edit alamat pengiriman
     */
    public function alamatEdit($checkoutId)
{
    $pengiriman = Pengiriman::where('checkout_id', $checkoutId)
        ->where('user_id', Auth::id())
        ->first();

    if (!$pengiriman) {
        // Redirect ke halaman tambah alamat
        return redirect()->route('user.pengiriman.alamat.create', $checkoutId)
            ->with('warning', 'Alamat belum tersedia, silakan tambahkan dulu.');
    }

    $checkout = $pengiriman->checkout;

    return view('user.pengiriman.form', compact('pengiriman', 'checkout'));
}


    /**
     * Simpan alamat pengiriman
     */
    public function alamatStore(Request $request, $checkoutId)
    {
        $request->validate([
            'nama_lengkap'     => 'required|string|max:255',
            'alamat_penerima'  => 'required|string|max:255',
            'cities'           => 'nullable|string|max:100',
            'kode_pos'         => 'nullable|string|max:10',
            'nomor_wa'         => 'nullable|string|max:20',
        ]);

        $checkout = Checkout::where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        Pengiriman::updateOrCreate(
            ['checkout_id' => $checkout->id],
            [
                'user_id'         => Auth::id(),
                'produk_id'       => $checkout->produk_id,
                'toko_id'         => $checkout->toko_id,
                'nama_lengkap'    => $request->nama_lengkap,
                'alamat_penerima' => $request->alamat_penerima,
                'cities'          => $request->cities,
                'kode_pos'        => $request->kode_pos,
                'nomor_wa'        => $request->nomor_wa,
            ]
        );

        return redirect()->route('user.pengiriman.kurir.edit', $checkout->id)
            ->with('success', 'Alamat pengiriman berhasil disimpan.');
    }

    /**
     * Form edit kurir & ongkir
     */
    public function kurirEdit($checkoutId)
    {
        $pengiriman = Pengiriman::where('checkout_id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $checkout = $pengiriman->checkout;

        return view('user.pengiriman.kurir', compact('pengiriman', 'checkout'));
    }

    /**
     * Simpan kurir & ongkir
     */
    public function kurirUpdate(Request $request, $checkoutId)
    {
        $request->validate([
            'kurir'   => 'required|string|max:50',
            'layanan' => 'required|string|max:50',
            'ongkir'  => 'required|integer|min:0',
        ]);

        $pengiriman = Pengiriman::where('checkout_id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pengiriman->update([
            'kurir'   => $request->kurir,
            'layanan' => $request->layanan,
            'ongkir'  => $request->ongkir,
        ]);

        return redirect()->route('user.checkout.create', $checkoutId)
            ->with('success', 'Kurir berhasil disimpan.');
    }
}
