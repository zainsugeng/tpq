<?php

namespace App\Http\Controllers;

use App\Models\Pelajaran;
use Illuminate\Http\Request;

class PelajaranController extends Controller
{
    public function index()
    {
        $pelajaran = Pelajaran::orderBy('urutan')->get();
        return view('admin.pelajaran.index', compact('pelajaran'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'   => ['required', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;

        Pelajaran::create($data);
        return back()->with('sukses', 'Pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Pelajaran $pelajaran)
    {
        $data = $request->validate([
            'nama'   => ['required', 'string', 'max:255'],
            'urutan' => ['nullable', 'integer'],
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;

        $pelajaran->update($data);
        return back()->with('sukses', 'Pelajaran berhasil diperbarui.');
    }

    public function destroy(Pelajaran $pelajaran)
    {
        $pelajaran->delete();
        return back()->with('sukses', 'Pelajaran berhasil dihapus.');
    }
}
