<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\Pelajaran;
use Illuminate\Http\Request;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $cari        = $request->input('cari');
        $pelajaranId = $request->input('pelajaran');

        $modul = Modul::with('pelajaran')
            ->when($cari, fn($q) => $q->where('nama', 'like', "%{$cari}%"))
            ->when($pelajaranId, fn($q) => $q->where('pelajaran_id', $pelajaranId))
            ->orderBy('pelajaran_id')
            ->orderBy('urutan')
            ->paginate(10)
            ->withQueryString();

        $pelajaran = Pelajaran::orderBy('urutan')->get();   // buat dropdown (modal + filter)
        return view('admin.modul.index', compact('modul', 'pelajaran'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pelajaran_id' => ['required', 'exists:pelajaran,id'],
            'nama'         => ['required', 'string', 'max:255'],
            'urutan'       => ['nullable', 'integer'],
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['aktif']  = $request->boolean('aktif');

        Modul::create($data);
        return back()->with('sukses', 'Modul berhasil ditambahkan.');
    }

    public function update(Request $request, Modul $modul)
    {
        $data = $request->validate([
            'pelajaran_id' => ['required', 'exists:pelajaran,id'],
            'nama'         => ['required', 'string', 'max:255'],
            'urutan'       => ['nullable', 'integer'],
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['aktif']  = $request->boolean('aktif');

        $modul->update($data);
        return back()->with('sukses', 'Modul berhasil diperbarui.');
    }

    public function destroy(Modul $modul)
    {
        $modul->delete();
        return back()->with('sukses', 'Modul berhasil dihapus.');
    }
}
