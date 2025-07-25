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
        $chat = Chat::with(['user', 'toko', 'pesan'])->findOrFail($id);

        // contoh: menandai pesan lawan bicara sebagai sudah dibaca
        foreach ($chat->pesan as $pesan) {
            if ($pesan->user_id !== auth()->id() && !$pesan->sudah_dibaca) {
                $pesan->update(['sudah_dibaca' => true]);
            }
        }

        return view('user.chat.tampil', compact('chat'));
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
}
