<?php

namespace App\Http\Controllers;

use App\Models\Pelajaran;

class HomeController extends Controller
{
    public function index()
    {
        $pelajaran = Pelajaran::where('guru_id', auth()->user()->guru_id)  // cuma pelajaran dari guru si murid
            ->orderBy('urutan')
            ->get();

        return view('murid.home', compact('pelajaran'));
    }
}
