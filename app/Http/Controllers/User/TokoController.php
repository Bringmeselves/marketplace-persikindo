<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokoController extends Controller
{
    // Menampilkan form buat toko
    public function create()
    {
        return view('user.toko.create');
    }

    // Menyimpan toko baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:toko,slug',
            'alamat' => 'required|string|max:500',
            'foto_toko' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('nama_toko', 'slug', 'alamat');

        if ($request->hasFile('foto_toko')) {
            $data['foto_toko'] = $request->file('foto_toko')->store('foto_toko', 'public');
        }

        $data['user_id'] = Auth::id();

        Toko::create($data);

        return redirect()->route('dashboard')->with('success', 'Toko berhasil dibuat.');
    }
}
