<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
    // Tampilkan form (tampilan beda untuk admin & murid)
    public function edit(Request $request)
    {
        $view = $request->user()->role === 'admin'
            ? 'auth.ubah-password-admin'
            : 'auth.ubah-password-murid';

        return view($view);
    }

    // Proses ubah password
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'min:6', 'confirmed'],
        ], [
            'current_password.required'         => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini salah.',
            'password.required'                 => 'Password baru wajib diisi.',
            'password.min'                      => 'Password baru minimal 6 karakter.',
            'password.confirmed'                => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();
        $user->password = $request->password; // cast 'hashed' meng-hash otomatis
        $user->save();

        return back()->with('sukses', 'Password berhasil diubah.');
    }
}
