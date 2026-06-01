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
        $statistik = [
            'murid'     => User::where('role', 'murid')->count(),
            'pelajaran' => Pelajaran::count(),
            'modul'     => Modul::count(),
            'materi'    => Materi::count(),
        ];

        $muridTerbaru = User::where('role', 'murid')->latest()->take(5)->get();

        return view('admin.dashboard', compact('statistik', 'muridTerbaru'));
    }
}
