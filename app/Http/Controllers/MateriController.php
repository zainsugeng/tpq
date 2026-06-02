<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Modul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $cari    = $request->input('cari');
        $modulId = $request->input('modul');

        $materi = Materi::with('modul')
            ->when($cari, function ($q) use ($cari) {
                $q->where(function ($sub) use ($cari) {
                    $sub->where('label', 'like', "%{$cari}%")
                        ->orWhere('teks_arab', 'like', "%{$cari}%");
                });
            })
            ->when($modulId, fn($q) => $q->where('modul_id', $modulId))
            ->orderBy('modul_id')
            ->orderBy('urutan')
            ->paginate(10)
            ->withQueryString();

        $modul = Modul::orderBy('nama')->get();   // dropdown (modal + filter)
        return view('admin.materi.index', compact('materi', 'modul'));
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        if ($request->hasFile('audio'))  $data['audio']  = $request->file('audio')->store('audio', 'public');
        if ($request->hasFile('gambar')) $data['gambar'] = $request->file('gambar')->store('gambar', 'public');
        $data['urutan'] = $data['urutan'] ?? 0;

        Materi::create($data);
        return back()->with('sukses', 'Materi berhasil ditambahkan.');
    }

    public function update(Request $request, Materi $materi)
    {
        $data = $this->validasi($request);

        // file lama cuma diganti kalau ada upload baru
        if ($request->hasFile('audio')) {
            if ($materi->audio) Storage::disk('public')->delete($materi->audio);
            $data['audio'] = $request->file('audio')->store('audio', 'public');
        }
        if ($request->hasFile('gambar')) {
            if ($materi->gambar) Storage::disk('public')->delete($materi->gambar);
            $data['gambar'] = $request->file('gambar')->store('gambar', 'public');
        }
        $data['urutan'] = $data['urutan'] ?? 0;

        $materi->update($data);
        return back()->with('sukses', 'Materi berhasil diperbarui.');
    }

    public function destroy(Materi $materi)
    {
        if ($materi->audio)  Storage::disk('public')->delete($materi->audio);
        if ($materi->gambar) Storage::disk('public')->delete($materi->gambar);
        $materi->delete();
        return back()->with('sukses', 'Materi berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $request->validate([
            'modul_id'    => ['required', 'exists:modul,id'],
            'tipe_konten' => ['required', 'in:teks,gambar'],
            'label'       => ['required', 'string', 'max:255'],
            'teks_arab'   => ['nullable', 'string', 'max:255'],
            'gambar'      => ['nullable', 'image', 'max:2048'],          // maks 2MB
            'audio'       => ['nullable', 'mimes:mp3,wav,ogg,m4a', 'max:5120'], // maks 5MB
            'urutan'      => ['nullable', 'integer'],
        ]);

        // balikin field NON-file aja (biar file lama nggak ke-null saat edit)
        return $request->only(['modul_id', 'tipe_konten', 'label', 'teks_arab', 'urutan']);
    }
}
