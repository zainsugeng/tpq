<?php

namespace App\Http\Controllers;

use App\Models\Modul;

class BelajarController extends Controller
{
    public function index(Modul $modul)
    {
        $materi = $modul->materi()->orderBy('urutan')->get();
        return view('murid.flashcard', compact('modul', 'materi'));
    }
}
