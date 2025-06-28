<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Pesan;
use App\Models\Toko;

class ChatController extends Controller
{
    // Menampilkan daftar chat berdasarkan role user (pembeli/penjual)
    public function index()
    {
        $userId = auth()->id();
        $toko = Toko::where('user_id', $userId)->first();

        if ($toko) {
            // Jika user punya toko → tampilkan daftar chat masuk dari pembeli
            $daftarChat = Chat::with('user', 'pesan')
                ->where('toko_id', $toko->id)
                ->latest()
                ->get();
        } else {
            // Jika user tidak punya toko → tampilkan chat yang ia buat ke toko
            $daftarChat = Chat::with('toko', 'pesan')
                ->where('user_id', $userId)
                ->latest()
                ->get();
        }

        return view('user.chat.index', compact('daftarChat', 'toko'));
    }

    // Menampilkan isi chat tertentu
    public function tampil($id)
    {
        $chat = Chat::with('pesan.user', 'toko')->findOrFail($id);
        $userId = auth()->id();

        if ($chat->user_id !== $userId && $chat->toko->user_id !== $userId) {
            abort(403); // Akses ditolak jika bukan pemilik chat atau toko
        }

        return view('user.chat.tampil', compact('chat'));
    }

    // Mengirim pesan baru
    public function kirimPesan(Request $request, $id)
    {
        $request->validate([
            'isi_pesan' => 'required|string',
        ]);

        $chat = Chat::with('toko')->findOrFail($id);
        $userId = auth()->id();

        if ($chat->user_id !== $userId && $chat->toko->user_id !== $userId) {
            abort(403);
        }

        $chat->pesan()->create([
            'user_id' => $userId,
            'isi_pesan' => $request->isi_pesan,
            'sudah_dibaca' => false,
        ]);

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
}
