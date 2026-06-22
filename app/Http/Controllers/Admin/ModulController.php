<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modul;
use App\Models\Pelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModulController extends Controller
{
    public function index(Request $request)
    {
        $cari        = $request->input('cari');
        $pelajaranId = $request->input('pelajaran');

        $modul = Modul::with('pelajaran')
            ->whereHas('pelajaran', fn($q) => $q->where('guru_id', auth()->id()))  // cuma modul dari pelajaran si admin
            ->when($cari, fn($q) => $q->where('nama', 'like', "%{$cari}%"))
            ->when($pelajaranId, fn($q) => $q->where('pelajaran_id', $pelajaranId))
            ->orderBy('pelajaran_id')
            ->orderBy('urutan')
            ->paginate(10)
            ->withQueryString();

        $pelajaran = Pelajaran::where('guru_id', auth()->id())->orderBy('urutan')->get();   // dropdown: cuma pelajaran si admin
        return view('admin.modul.index', compact('modul', 'pelajaran'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pelajaran_id' => ['required', Rule::exists('pelajaran', 'id')->where('guru_id', auth()->id())],  // pelajaran HARUS punya admin ini
            'nama'         => ['required', 'string', 'max:255', Rule::unique('modul', 'nama')->where(fn($q) => $q->where('pelajaran_id', $request->pelajaran_id))],
            'urutan'       => ['nullable', 'integer'],
        ], [
            'pelajaran_id.exists' => 'Pelajaran tidak valid.',
            'nama.unique'         => 'Nama modul ini sudah dipakai di pelajaran tersebut.',
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['aktif']  = $request->boolean('aktif');

        Modul::create($data);
        return back()->with('sukses', 'Modul berhasil ditambahkan.');
    }

    public function update(Request $request, Modul $modul)
    {
        abort_unless($modul->pelajaran->guru_id == auth()->id(), 403);   // cegah edit modul admin lain

        $data = $request->validate([
            'pelajaran_id' => ['required', Rule::exists('pelajaran', 'id')->where('guru_id', auth()->id())],
            'nama'         => ['required', 'string', 'max:255', Rule::unique('modul', 'nama')->where(fn($q) => $q->where('pelajaran_id', $request->pelajaran_id))->ignore($modul->id)],
            'urutan'       => ['nullable', 'integer'],
        ], [
            'pelajaran_id.exists' => 'Pelajaran tidak valid.',
            'nama.unique'         => 'Nama modul ini sudah dipakai di pelajaran tersebut.',
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['aktif']  = $request->boolean('aktif');

        $modul->update($data);
        return back()->with('sukses', 'Modul berhasil diperbarui.');
    }

    public function destroy(Modul $modul)
    {
        abort_unless($modul->pelajaran->guru_id == auth()->id(), 403);   // cegah hapus modul admin lain

        $modul->delete();
        return back()->with('sukses', 'Modul berhasil dihapus.');
    }
}
