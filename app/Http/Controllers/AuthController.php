<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Tampilkan form login murid
    public function formMurid()
    {
        return view('auth.login');
    }

    // Tampilkan form login admin
    public function formAdmin()
    {
        return view('auth.login-admin');
    }

    // Proses login murid (hanya akun murid yang boleh masuk di sini)
    public function loginMurid(Request $request)
    {
        $kredensial = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        $kredensial['role'] = 'murid';

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
    }

    // Proses login admin (hanya akun admin yang boleh masuk di sini)
    public function loginAdmin(Request $request)
    {
        $kredensial = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        $kredensial['role'] = 'admin';

        if (Auth::attempt($kredensial)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->onlyInput('username');
    }

    // Logout — balik ke login sesuai role
    public function logout(Request $request)
    {
        $adalahAdmin = Auth::check() && Auth::user()->role === 'admin';

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($adalahAdmin ? 'admin.login' : 'login');
    }
}
