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
            ->where('guru_id', auth()->id())          // cuma murid milik admin yang login
            ->when($cari, function ($q) use ($cari) {
                $q->where(function ($sub) use ($cari) {
                    $sub->where('nama', 'like', "%{$cari}%")
                        ->orWhere('username', 'like', "%{$cari}%")
                        ->orWhere('nama_ortu', 'like', "%{$cari}%");
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
            'nama'          => ['required', 'string', 'max:255'],
            'nama_ortu'     => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date'],
            'username'      => ['required', 'string', 'max:255', 'unique:users,username'],
            'password'      => ['required', 'string', 'min:4'],
        ], $this->pesan());
        $data['role']    = 'murid';
        $data['guru_id'] = auth()->id();              // murid ini dipegang admin yang login

        User::create($data);
        return back()->with('sukses', 'Akun murid berhasil dibuat.');
    }

    public function update(Request $request, User $murid)
    {
        abort_unless($murid->guru_id == auth()->id(), 403);   // cegah edit murid admin lain

        $data = $request->validate([
            'nama'          => ['required', 'string', 'max:255'],
            'nama_ortu'     => ['required', 'string', 'max:255'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date'],
            'username'      => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($murid->id)],
            'password'      => ['nullable', 'string', 'min:4'],
        ], $this->pesan());

        if (empty($data['password'])) {
            unset($data['password']);   // password cuma diganti kalau diisi
        }

        $murid->update($data);
        return back()->with('sukses', 'Akun murid berhasil diperbarui.');
    }

    public function destroy(User $murid)
    {
        abort_unless($murid->guru_id == auth()->id(), 403);   // cegah hapus murid admin lain

        $murid->delete();
        return back()->with('sukses', 'Akun murid berhasil dihapus.');
    }

    private function pesan(): array
    {
        return [
            'required'           => 'Bagian ini wajib diisi.',
            'username.unique'    => 'Username ini sudah dipakai.',
            'jenis_kelamin.in'   => 'Jenis kelamin tidak valid.',
            'tanggal_lahir.date' => 'Tanggal lahir tidak valid.',
            'password.min'       => 'Password minimal 4 karakter.',
        ];
    }
}
