<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penarikan;

class PenarikanController extends Controller
{
    public function index()
    {
        $penarikan = Penarikan::latest()->paginate(10);
        return view('admin.penarikan.index', compact('penarikan'));
    }

    public function show($id)
    {
        $penarikan = Penarikan::findOrFail($id);
        return view('admin.penarikan.show', compact('penarikan'));
    }

    public function update(Request $request, $id)
    {
        $penarikan = Penarikan::findOrFail($id);

        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
            $penarikan->bukti_transfer = $path;
            $penarikan->status = 'disetujui';
            $penarikan->save();
        }

        return redirect()->route('admin.penarikan.index')->with('success', 'Penarikan disetujui dan bukti transfer diunggah.');
    }

    public function reject(Request $request, $id)
    {
        $penarikan = Penarikan::findOrFail($id);

        $request->validate([
            'catatan' => 'nullable|string|max:1000',
        ]);

        $penarikan->status = 'ditolak';
        $penarikan->catatan = $request->catatan;
        $penarikan->save();

        return redirect()->route('admin.penarikan.index')->with('success', 'Penarikan berhasil ditolak.');
    }
}

