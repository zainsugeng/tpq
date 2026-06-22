<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelajaran;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AkunAdminController extends Controller
{
    public function index(Request $request)
    {
        $cari = $request->input('cari');

        $admin = User::where('role', 'admin')
            ->when($cari, function ($q) use ($cari) {
                $q->where(function ($sub) use ($cari) {
                    $sub->where('nama', 'like', "%{$cari}%")
                        ->orWhere('username', 'like', "%{$cari}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.akun-admin.index', compact('admin'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
        ], $this->pesan());
        $data['role'] = 'admin';   // akun baru = admin (guru_id biarin null)

        User::create($data);
        return back()->with('sukses', 'Akun admin berhasil dibuat.');
    }

    public function update(Request $request, User $admin)
    {
        abort_unless($admin->role === 'admin', 403);   // mastiin yang diedit emang admin

        $data = $request->validate([
            'nama'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($admin->id)],
            'password' => ['nullable', 'string', 'min:6'],
        ], $this->pesan());

        if (empty($data['password'])) {
            unset($data['password']);   // password cuma diganti kalau diisi (ini juga = reset cara 3)
        }

        $admin->update($data);
        return back()->with('sukses', 'Akun admin berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        abort_unless($admin->role === 'admin', 403);

        // 1) Nggak boleh hapus diri sendiri
        if ($admin->id == auth()->id()) {
            return back()->with('gagal', 'Tidak bisa menghapus akun sendiri.');
        }

        // 2) Nggak boleh hapus admin yang masih punya murid/pelajaran (biar nggak ada data nyangkut)
        $punyaMurid     = User::where('role', 'murid')->where('guru_id', $admin->id)->exists();
        $punyaPelajaran = Pelajaran::where('guru_id', $admin->id)->exists();
        if ($punyaMurid || $punyaPelajaran) {
            return back()->with('gagal', 'Admin ini masih punya murid/pelajaran. Akun baru bisa dihapus kalau murid & pelajarannya sudah kosong.');
        }

        $admin->delete();
        return back()->with('sukses', 'Akun admin berhasil dihapus.');
    }

    private function pesan(): array
    {
        return [
            'required'        => 'Bagian ini wajib diisi.',
            'username.unique' => 'Username ini sudah dipakai.',
            'password.min'    => 'Password minimal 6 karakter.',
        ];
    }
}
