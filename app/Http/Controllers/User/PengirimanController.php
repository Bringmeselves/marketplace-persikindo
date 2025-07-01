<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\Pengiriman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    $origin = $checkout->toko->origin;
    $destination = $pengiriman->cities;
    $weight = $checkout->berat_total ?? 1000;

    // Panggil API Komerce untuk mendapatkan daftar kurir (shipping_name)
    $response = Http::withHeaders([
        'x-api-key' => env('KOMERCE_API_KEY'),
    ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/calculate', [
        'shipper_destination_id'   => $origin,
        'receiver_destination_id'  => $destination,
        'weight'                   => $weight,
        'item_value'               => 1000,
        'cod'                      => 'no',
    ]);

    if (!$response->ok()) {
        return back()->with('error', 'Gagal memuat daftar kurir');
    }

    $data = $response->json('data');

    $allOptions = array_merge(
        $data['calculate_reguler'] ?? [],
        $data['calculate_cargo'] ?? []
    );

    $kurirList = collect($allOptions)
        ->pluck('shipping_name')
        ->filter()
        ->unique()
        ->map(function ($name) {
            return [
                'code' => strtolower(str_replace(' ', '_', $name)),
                'name' => $name
            ];
        })
        ->values()
        ->all();

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
            'cities'        => 'required|numeric',
            'berat'         => 'required|numeric|min:1',
            'kurir'         => 'required|string',
            'checkout_id'   => 'required|exists:checkout,id',
        ]);

        try {
            // Ambil data checkout beserta produk dan toko
            $checkout = Checkout::with(['produk', 'toko'])->findOrFail($request->checkout_id);

            // Ambil origin dari toko terkait checkout
            $origin = $checkout->toko->origin ?? null;

            if (!$origin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Origin tidak ditemukan pada toko terkait.',
                ], 422);
            }

        \Log::info('Origin:', [$origin]);
        \Log::info('Destination:', [$request->cities]);
        \Log::info('Berat:', [$request->berat]);
        \Log::info('Kurir:', [$request->kurir]);

            $item_value = 1000;

            // Panggil API ongkir Komerce
            $response = Http::withHeaders([
                'x-api-key' => env('KOMERCE_API_KEY'),
            ])->get('https://api-sandbox.collaborator.komerce.id/tariff/api/v1/calculate', [
                'shipper_destination_id'   => $origin,
                'receiver_destination_id'  => $request->cities,
                'weight'                   => $request->berat,
                'item_value'               => $item_value,
                'cod'                      => 'no',
            ]);

            if (!$response->ok()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Gagal menghitung ongkir: ' . ($response['meta']['message'] ?? 'Unknown error'),
                ], 400);
            }

            $data = $response->json('data');

            // Gabungkan hasil reguler dan cargo
            $allOptions = array_merge(
        $data['calculate_reguler'] ?? [],
        $data['calculate_cargo'] ?? []
    );

    $filtered = collect($allOptions)->filter(function ($item) use ($request) {
        return Str::contains(strtolower($item['shipping_name'] ?? ''), strtolower($request->kurir));
    })->values();

    $formatted = $filtered->map(function ($item) {
        return [
            'service_name' => strtoupper($item['shipping_name'] ?? 'UNKNOWN') . ' - ' . ($item['service_name'] ?? 'SERVICE'),
            'tariff'       => isset($item['shipping_cost']) ? round($item['shipping_cost'] / 100) : 0,
            'cashback'     => isset($item['shipping_cashback']) ? round($item['shipping_cashback'] / 100) : 0,
            'net_cost'     => isset($item['shipping_cost_net']) ? round($item['shipping_cost_net'] / 100) : 0,
            'grandtotal'   => isset($item['grandtotal']) ? round($item['grandtotal'] / 100) : 0,
            'etd'          => $item['etd'] ?? '-',
        ];
    });


    return response()->json([
        'status' => 'success',
        'data'   => $formatted,
    ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getTarifOngkir(Request $request)
    {
        $origin = (int) $request->input('origin.0', $request->input('origin', 0));
        $destination = (int) $request->input('cities.0', $request->input('cities', 0));
        $weight = (int) $request->input('berat.0', $request->input('berat', 1000));
        $couriers = is_array($request->kurir) ? $request->kurir : [$request->kurir];

        $results = [];

        foreach ($couriers as $courier) {
            // Logging request
            Log::debug('Tarif Request Payload', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ]);

            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'api-key' => 'LT17D1mxfee993deed007adcvWYZZIH3',
            ])->post('https://collaborator.komerce.id/api/v1/shipping/delivery', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier,
            ]);

            // Logging response
            Log::debug('Tarif API Response', [
                'courier' => $courier,
                'response' => $response->json()
            ]);

            if ($response->successful()) {
                $data = $response->json('data') ?? [];
                $results = array_merge($results, $data);
            }
        }

        // Filter hasil dan format ke Rupiah
        $filtered = collect($results)->filter(function ($item) {
            return isset($item['shipping_cost']);
        });

        $formatted = $filtered->map(function ($item) {
            return [
                'service_name' => strtoupper($item['shipping_name'] ?? 'UNKNOWN') . ' - ' . ucwords(strtolower($item['service_type'] ?? 'SERVICE')),
                'tariff'       => isset($item['shipping_cost']) ? ((int) $item['shipping_cost']) / 100 : 0,
                'etd'          => $item['etd'] ?? 'Tidak diketahui',
            ];
        })->values();

        return response()->json($formatted);
    }
}