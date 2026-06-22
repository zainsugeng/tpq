<?php

namespace App\Http\Controllers;

use App\Models\Modul;
use App\Models\Progres;

class QuizController extends Controller
{
    // Tampilkan kuis (soal di-generate otomatis)
    public function index(Modul $modul)
    {
        abort_unless($modul->pelajaran->guru_id == auth()->user()->guru_id, 403);   // cegah kuis dari materi guru lain

        $semua = $modul->materi()->get();

        $jumlahSoal = min(5, $semua->count());
        $soalMateri = $semua->shuffle()->take($jumlahSoal)->values();

        $soal = $soalMateri->map(function ($benar) use ($semua) {
            $opsi = $semua->where('id', '!=', $benar->id)
                ->shuffle()->take(2)
                ->push($benar)
                ->shuffle()
                ->map(fn($o) => [
                    'id'     => $o->id,
                    'tipe'   => $o->tipe_konten,
                    'teks'   => $o->teks_arab,
                    'gambar' => $o->gambar ? asset('storage/' . $o->gambar) : null,
                ])
                ->values();

            return [
                'benarId' => $benar->id,
                'label'   => $benar->label,
                'audio'   => $benar->audio ? asset('storage/' . $benar->audio) : null,
                'opsi'    => $opsi->all(),
            ];
        })->values();

        return view('murid.kuis', compact('modul', 'soal'));
    }

    // Proses saat kuis selesai
    public function selesai(Modul $modul)
    {
        $user = auth()->user();

        abort_unless($modul->pelajaran->guru_id == $user->guru_id, 403);   // cegah nyelesaiin modul guru lain

        // 1) Catat progres (cuma sekali per modul)
        $progres = Progres::firstOrCreate(
            ['murid_id' => $user->id, 'modul_id' => $modul->id],
            ['selesai_pada' => now()]
        );
        $baruSelesai = $progres->wasRecentlyCreated;

        // 2) Update streak
        $terakhir   = $user->tanggal_terakhir_aktif;
        $streakNaik = is_null($terakhir) || ! $terakhir->isToday();  // pertama kali hari ini?

        if (is_null($terakhir)) {
            $user->jumlah_streak = 1;
        } elseif ($terakhir->isToday()) {
            // tetap
        } elseif ($terakhir->isYesterday()) {
            $user->jumlah_streak += 1;
        } else {
            $user->jumlah_streak = 1;
        }
        $user->tanggal_terakhir_aktif = today();
        $user->save();

        // 3) Cari modul berikutnya (buat pesan "terbuka")
        $modulBerikut = Modul::where('pelajaran_id', $modul->pelajaran_id)
            ->where('aktif', true)
            ->where('urutan', '>', $modul->urutan)
            ->orderBy('urutan')
            ->first();

        // 4) Ke halaman hasil
        return redirect()->route('hasil', $modul->id)->with([
            'streak'       => $user->jumlah_streak,
            'streakNaik'   => $streakNaik,
            'modulBerikut' => ($baruSelesai && $modulBerikut) ? $modulBerikut->nama : null,
        ]);
    }

    // Halaman hasil
    public function hasil(Modul $modul)
    {
        abort_unless($modul->pelajaran->guru_id == auth()->user()->guru_id, 403);   // cegah liat hasil modul guru lain

        return view('murid.hasil', compact('modul'));
    }
}
