<?php

namespace App\Http\Controllers;

use App\Models\Pelajaran;
use App\Models\Progres;

class ModulController extends Controller
{
    public function index(Pelajaran $pelajaran)
    {
        $user = auth()->user();

        // ID modul yang SUDAH diselesaikan murid ini
        $selesaiIds = Progres::where('murid_id', $user->id)
            ->pluck('modul_id')
            ->toArray();

        // Modul aktif di pelajaran ini, urut + bawa materinya
        $moduls = $pelajaran->modul()
            ->where('aktif', true)
            ->with('materi')
            ->orderBy('urutan')
            ->get();

        // === LOGIKA GEMBOK ===
        $sebelumnyaSelesai = true;   // modul pertama selalu terbuka
        $jumlahSelesai = 0;

        foreach ($moduls as $m) {
            $sudahSelesai = in_array($m->id, $selesaiIds);

            if ($sudahSelesai) {
                $m->status = 'selesai';
                $jumlahSelesai++;
            } elseif ($sebelumnyaSelesai) {
                $m->status = 'terbuka';
            } else {
                $m->status = 'terkunci';
            }

            $sebelumnyaSelesai = $sudahSelesai;  // berikutnya kebuka kalau INI selesai
        }

        return view('murid.modul', compact('pelajaran', 'moduls', 'jumlahSelesai'));
    }
}
