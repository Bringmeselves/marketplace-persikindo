<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Pesan;
use App\Models\Transaksi;
use App\Models\Toko;

class ChatController extends Controller
{
    // Menampilkan daftar chat berdasarkan role user (pembeli/penjual)
    public function index()
    {
        $userId = auth()->id();
        $toko = Toko::where('user_id', $userId)->first();

        $daftarChat = Chat::with([
        'user',
        'toko',
        'pesan' => fn($q) => $q->latest(),
        'pesanBelumDibaca' => fn($q) => $q->where('user_id', '!=', $userId) // pesan dari lawan bicara
        ])
        ->where(function ($query) use ($userId, $toko) {
            $query->where('user_id', $userId); // Sebagai pembeli
            if ($toko) {
                $query->orWhere('toko_id', $toko->id); // Sebagai penjual
            }
        })
        ->whereHas('pesan') // hanya chat yang punya isi pesan
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('user.chat.index', compact('daftarChat', 'toko'));
    }

    // Menampilkan isi chat tertentu
    public function tampil($id)
    {
       $chat = Chat::with([
            'user',
            'toko',
            'pesan.transaksi.checkout.item.produk',
            'pesan.transaksi.checkout.item.varian'
        ])->findOrFail($id);

        // Tandai pesan lawan bicara sebagai sudah dibaca
        foreach ($chat->pesan as $pesan) {
            if ($pesan->user_id !== auth()->id() && !$pesan->sudah_dibaca) {
                $pesan->update(['sudah_dibaca' => true]);
            }
        }

        // Ambil default_message dari session (jika pesan auto)
        $defaultMessage = session('default_message');
        $transaksi = null;

        // Coba ambil ID transaksi dari isi pesan
        if ($defaultMessage && preg_match('/#(\d+)/', $defaultMessage, $matches)) {
            $transaksiId = $matches[1];

            $transaksi = Transaksi::with('checkout.item.produk', 'checkout.item.varian')
                            ->where('id', $transaksiId)
                            ->where('user_id', auth()->id())
                            ->first();
        }

        return view('user.chat.tampil', compact('chat', 'transaksi'));
    }

    // Mengirim pesan baru
    public function kirimPesan(Request $request, $id)
    {
        $request->validate([
            'isi_pesan' => 'nullable|string',
            'file' => 'nullable|file|max:5120', // max 5MB
        ]);

        $chat = Chat::with('toko')->findOrFail($id);
        $userId = auth()->id();

        if ($chat->user_id !== $userId && $chat->toko->user_id !== $userId) {
            abort(403);
        }

        $pesanData = [
            'user_id' => $userId,
            'isi_pesan' => $request->isi_pesan,
            'sudah_dibaca' => false,
        ];

        // Jika ada file diinput
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('pesan_files', 'public');
            $pesanData['file_path'] = $path;
            $pesanData['file_type'] = $file->getClientMimeType();
            $pesanData['file_name'] = $file->getClientOriginalName();
        }

        // Simpan pesan
        $chat->pesan()->create($pesanData);

        // Update timestamp chat agar index terurut ulang
        $chat->touch();

        return back()->with('success', 'Pesan berhasil dikirim.');
    }

    // Membuat chat baru (oleh pembeli), lalu redirect ke halaman chat
    public function mulaiChat($tokoId)
    {
        $toko = Toko::findOrFail($tokoId);

        // Jangan izinkan chat ke toko milik sendiri
        if ($toko->user_id == auth()->id()) {
            return redirect()->back()->with('error', 'Kamu tidak bisa mengirim pesan ke toko milikmu sendiri.');
        }

        // Ambil atau buat chat baru
        $chat = Chat::firstOrCreate([
            'user_id' => auth()->id(),
            'toko_id' => $tokoId,
        ]);

        return redirect()->route('user.chat.tampil', $chat->id);
    }

    public function ajukanKeluhan($transaksiId)
    {
        $user = auth()->user();

        $transaksi = Transaksi::with('produk.toko')->where('id', $transaksiId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        if ($transaksi->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Keluhan hanya bisa diajukan untuk transaksi yang sudah dikirim.');
        }

        $toko = $transaksi->produk->toko;

        if (!$toko || $toko->user_id === $user->id) {
            return redirect()->back()->with('error', 'Tidak bisa menghubungi toko milik sendiri.');
        }

        // Ambil atau buat chat
        $chat = Chat::firstOrCreate([
            'user_id' => $user->id,
            'toko_id' => $toko->id,
        ]);

        // Ambil atau buat chat
        $chat = Chat::firstOrCreate([
            'user_id' => $user->id,
            'toko_id' => $toko->id,
        ]);

        // Kirim otomatis pesan ringkasan transaksi
        Pesan::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'isi_pesan' => '-',
            'is_ringkasan_transaksi' => true,
            'transaksi_id' => $transaksi->id,
        ]);

        // Buat auto-isi pesan (tanpa dikirim)
        $defaultText = "Halo *{$toko->nama_toko}*, saya ingin mengajukan keluhan terkait pesanan saya dengan nomor transaksi *#{$transaksi->id}*. Mohon bantuannya. 🙏";

        return redirect()->route('user.chat.tampil', $chat->id)
            ->with('default_message', $defaultText);
    }
}
