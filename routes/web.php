<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModulController;
use App\Http\Controllers\BelajarController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelajaranController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\MuridController;
use App\Http\Controllers\Admin\ModulController as ModulAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

// === LOGIN MURID ===
Route::get('/login', [AuthController::class, 'formMurid'])->name('login');
Route::post('/login', [AuthController::class, 'loginMurid'])->name('login.proses');

// === LOGIN ADMIN (halaman & URL terpisah) ===
Route::get('/admin/login', [AuthController::class, 'formAdmin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginAdmin'])->name('admin.login.proses');

// === LOGOUT (dipakai dua-duanya) ===
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    // === MURID ===
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/pelajaran/{pelajaran}/modul', [ModulController::class, 'index'])->name('modul.index');
    Route::get('/modul/{modul}/belajar', [BelajarController::class, 'index'])->name('belajar.index');
    Route::get('/modul/{modul}/kuis', [QuizController::class, 'index'])->name('kuis.index');
    Route::post('/modul/{modul}/selesai', [QuizController::class, 'selesai'])->name('kuis.selesai');
    Route::get('/modul/{modul}/hasil', [QuizController::class, 'hasil'])->name('hasil');

    // === ADMIN ===
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('pelajaran', PelajaranController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('modul', ModulAdminController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('materi', MateriController::class)->only(['index', 'store', 'update', 'destroy']);
        Route::resource('murid', MuridController::class)->only(['index', 'store', 'update', 'destroy']);
    });
});
