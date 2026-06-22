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
            ->whereHas('modul.pelajaran', fn($q) => $q->where('guru_id', auth()->id()))  // materi dari modul→pelajaran si admin
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

        $modul = Modul::whereHas('pelajaran', fn($q) => $q->where('guru_id', auth()->id()))  // dropdown: modul si admin
            ->orderBy('nama')->get();
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
        abort_unless($materi->modul->pelajaran->guru_id == auth()->id(), 403);   // cegah edit materi admin lain

        $data = $this->validasi($request);

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
        abort_unless($materi->modul->pelajaran->guru_id == auth()->id(), 403);   // cegah hapus materi admin lain

        if ($materi->audio)  Storage::disk('public')->delete($materi->audio);
        if ($materi->gambar) Storage::disk('public')->delete($materi->gambar);
        $materi->delete();
        return back()->with('sukses', 'Materi berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        $request->validate([
            'modul_id'    => ['required', 'exists:modul,id', function ($attribute, $value, $fail) {
                $modul = Modul::with('pelajaran')->find($value);
                if (! $modul || $modul->pelajaran->guru_id != auth()->id()) {
                    $fail('Modul tidak valid atau bukan milik Anda.');
                }
            }],
            'tipe_konten' => ['required', 'in:teks,gambar'],
            'label'       => ['required', 'string', 'max:255'],
            'teks_arab'   => ['nullable', 'string', 'max:255'],
            'gambar'      => ['nullable', 'image', 'max:2048'],
            'audio'       => ['nullable', 'mimes:mp3,wav,ogg,m4a', 'max:5120'],
            'urutan'      => ['nullable', 'integer'],
        ]);

        return $request->only(['modul_id', 'tipe_konten', 'label', 'teks_arab', 'urutan']);
    }
}
