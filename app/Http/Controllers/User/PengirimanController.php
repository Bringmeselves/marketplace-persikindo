<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    /**
     * Tampilkan form tambah alamat pengiriman.
     */
    public function alamatCreate($checkoutId)
    {
        $checkout = Checkout::where('id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $response = Http::withHeaders([
            'x-api-key' => env('KOMERCE_API_KEY'),
            'Accept' => 'application/json',
        ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search');

        $data = $response->json();
        $cities = $data['data'] ?? [];

        return view('user.pengiriman.form', compact('checkout', 'cities'));
    }

    public function alamatEdit($checkoutId)
    {
        $pengiriman = Pengiriman::where('checkout_id', $checkoutId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$pengiriman) {
            return redirect()->route('user.pengiriman.alamat.create', $checkoutId)
                ->with('warning', 'Alamat belum tersedia, silakan tambahkan dulu.');
        }

        $checkout = $pengiriman->checkout;

        return view('user.pengiriman.form', compact('pengiriman', 'checkout'));
    }

    public function alamatStore(Request $request, $checkoutId)
    {
        $request->validate([
            'nama_lengkap'     => 'required|string|max:255',
            'alamat_penerima'  => 'required|string|max:255',
            'cities'           => 'required|string|max:100',
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

    public function kurirEdit($checkoutId)
    {
        $pengiriman = Pengiriman::where('checkout_id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $checkout = $pengiriman->checkout;

        $kurirList = [
            ['code' => 'jne', 'name' => 'JNE'],
            ['code' => 'pos', 'name' => 'POS Indonesia'],
            ['code' => 'tiki', 'name' => 'TIKI'],
        ];

        $origin = $checkout->toko->origin_city_id;
        $destination = $pengiriman->cities;
        $weight = $checkout->berat_total ?? 1000;

        return view('user.pengiriman.kurir', compact('pengiriman', 'checkout', 'kurirList', 'origin', 'destination', 'weight'));
    }

    public function kurirUpdate(Request $request, $checkoutId)
    {
        $request->validate([
            'kurir'   => 'required|string|max:50',
            'layanan' => 'required|string|max:50',
            'ongkir'  => 'required|numeric|min:0',
        ]);

        $pengiriman = Pengiriman::where('checkout_id', $checkoutId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $pengiriman->update([
            'kurir'   => $request->kurir,
            'layanan' => $request->layanan,
            'ongkir'  => (int) $request->ongkir,
        ]);

        return redirect()->route('user.checkout.create', $checkoutId)
            ->with('success', 'Kurir berhasil disimpan.');
    }

    /**
     * Ambil daftar kota (dengan pencarian keyword).
     */
    public function getCities(Request $request)
    {
        try {
            $defaultKeywords = [
                'bandung', 'bandung_barat', 'bekasi', 'kabupaten_bekasi',
                'bogor', 'kabupaten_bogor', 'cimahi', 'cirebon', 'kabupaten_cirebon',
                'depok', 'garut', 'indramayu', 'karawang', 'kuningan',
                'majalengka', 'pangandaran', 'purwakarta', 'subang', 'sukabumi',
                'kabupaten_sukabumi', 'sumedang', 'tasikmalaya', 'kabupaten_tasikmalaya'
            ];

            $keyword = $request->get('keyword');
            $keywordsToSearch = $keyword ? [$keyword] : $defaultKeywords;
            $allCities = [];

            foreach ($keywordsToSearch as $kw) {
                $response = Http::withHeaders([
                    'x-api-key' => env('KOMERCE_API_KEY'),
                    'Accept' => 'application/json',
                ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/destination/search', [
                    'keyword' => $kw,
                ]);

                if ($response->ok() && isset($response['data'])) {
                    $allCities = array_merge($allCities, $response['data']);
                }
            }

            $uniqueCities = collect($allCities)->unique('id')->values()->all();

            return response()->json([
                'status' => 'success',
                'data' => $uniqueCities,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data kota: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Hitung ongkir menggunakan API Komerce
     */
public function cekOngkir(Request $request)
{
    $request->validate([
        'origin'        => 'required|numeric',
        'cities'        => 'required|numeric',
        'berat'         => 'required|numeric|min:1',
        'kurir'         => 'required|string',
        'checkout_id'   => 'required|exists:checkout,id',
    ]);

    try {
        $checkout = Checkout::with('produk')->findOrFail($request->checkout_id);
        $item_value = (int) ($checkout->produk->harga ?? 50000);


        $response = Http::withHeaders([
            'x-api-key' => env('KOMERCE_API_KEY'),
        ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/calculate', [
            'shipper_destination_id' => $request->origin,
            'receiver_destination_id' => $request->cities,
            'weight' => $request->berat,
            'item_value' => $item_value,
            'cod' => 'no',
        ]);

        if (!$response->ok()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghitung ongkir: ' . ($response['meta']['message'] ?? 'Unknown error'),
            ], 400);
        }

        $data = $response->json('data');

        // Gabungkan reguler dan cargo
        $allOptions = array_merge(
            $data['calculate_reguler'] ?? [],
            $data['calculate_cargo'] ?? []
        );

        // Filter berdasarkan kurir yang diminta
        $filtered = collect($allOptions)
            ->where('shipping_name', strtoupper($request->kurir))
            ->values();

        return response()->json([
            'status' => 'success',
            'data' => $filtered,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
        ], 500);
    }
}

}
