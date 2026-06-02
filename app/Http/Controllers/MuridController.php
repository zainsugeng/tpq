<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MuridController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->input('cari');

        $murid = User::where('role', 'murid')
            ->when($cari, function ($q) use ($cari) {
                $q->where(function ($sub) use ($cari) {
                    $sub->where('nama', 'like', "%{$cari}%")
                        ->orWhere('username', 'like', "%{$cari}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.murid.index', compact('murid'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:4'],
        ]);
        $data['role'] = 'murid';

        User::create($data);
        return back()->with('sukses', 'Akun murid berhasil dibuat.');
    }

    public function update(Request $request, User $murid)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($murid->id)],
            'password' => ['nullable', 'string', 'min:4'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);   // password cuma diganti kalau diisi
        }

        $murid->update($data);
        return back()->with('sukses', 'Akun murid berhasil diperbarui.');
    }

    public function destroy(User $murid)
    {
        $murid->delete();
        return back()->with('sukses', 'Akun murid berhasil dihapus.');
    }
}
