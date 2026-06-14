<?php

namespace App\Http\Controllers;

use App\Models\Pelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PelajaranController extends Controller
{
    public function index(Request $request)
    {
        $cari  = $request->input('cari');
        $warna = $request->input('warna');

        $pelajaran = Pelajaran::query()
            ->when($cari, function ($q) use ($cari) {
                $q->where(function ($sub) use ($cari) {
                    $sub->where('nama', 'like', "%{$cari}%")
                        ->orWhere('subjudul', 'like', "%{$cari}%");
                });
            })
            ->when($warna, fn($q) => $q->where('warna', $warna))
            ->orderBy('urutan')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pelajaran.index', compact('pelajaran'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255', Rule::unique('pelajaran', 'nama')],
            'subjudul' => ['nullable', 'string', 'max:255'],
            'warna'    => ['nullable', 'in:emerald,rose,amber,sky,violet,teal'],
            'urutan'   => ['nullable', 'integer'],
        ], [
            'nama.unique' => 'Nama pelajaran ini sudah ada.',
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['warna']  = $data['warna'] ?? 'emerald';

        Pelajaran::create($data);
        return back()->with('sukses', 'Pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Pelajaran $pelajaran)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255', Rule::unique('pelajaran', 'nama')->ignore($pelajaran->id)],
            'subjudul' => ['nullable', 'string', 'max:255'],
            'warna'    => ['nullable', 'in:emerald,rose,amber,sky,violet,teal'],
            'urutan'   => ['nullable', 'integer'],
        ], [
            'nama.unique' => 'Nama pelajaran ini sudah ada.',
        ]);
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['warna']  = $data['warna'] ?? 'emerald';

        $pelajaran->update($data);
        return back()->with('sukses', 'Pelajaran berhasil diperbarui.');
    }

    public function destroy(Pelajaran $pelajaran)
    {
        $pelajaran->delete();
        return back()->with('sukses', 'Pelajaran berhasil dihapus.');
    }
}
