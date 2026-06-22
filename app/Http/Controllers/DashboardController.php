<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelajaran;
use App\Models\Modul;
use App\Models\Materi;

class DashboardController extends Controller
{
    public function index()
    {
        $guruId = auth()->id();

        $statistik = [
            'murid'     => User::where('role', 'murid')->where('guru_id', $guruId)->count(),
            'pelajaran' => Pelajaran::where('guru_id', $guruId)->count(),
            'modul'     => Modul::whereHas('pelajaran', fn($q) => $q->where('guru_id', $guruId))->count(),
            'materi'    => Materi::whereHas('modul.pelajaran', fn($q) => $q->where('guru_id', $guruId))->count(),
        ];

        $muridTerbaru = User::where('role', 'murid')->where('guru_id', $guruId)->latest()->take(5)->get();

        return view('admin.dashboard', compact('statistik', 'muridTerbaru'));
    }
}
