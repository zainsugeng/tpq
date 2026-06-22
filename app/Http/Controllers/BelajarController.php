<?php

namespace App\Http\Controllers;

use App\Models\Modul;

class BelajarController extends Controller
{
    public function index(Modul $modul)
    {
        abort_unless($modul->pelajaran->guru_id == auth()->user()->guru_id, 403);   // cegah belajar materi guru lain

        $materi = $modul->materi()->orderBy('urutan')->get();
        return view('murid.flashcard', compact('modul', 'materi'));
    }
}
