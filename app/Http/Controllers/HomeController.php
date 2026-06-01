<?php

namespace App\Http\Controllers;

use App\Models\Pelajaran;

class HomeController extends Controller
{
    public function index()
    {
        $pelajaran = Pelajaran::orderBy('urutan')->get();
        return view('murid.home', compact('pelajaran'));
    }
}
