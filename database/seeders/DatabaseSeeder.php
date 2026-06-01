<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pelajaran;
use App\Models\Modul;
use App\Models\Materi;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // --- Akun ---
        User::create([
            'nama' => 'Admin TPQ',
            'username' => 'admin',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Santri Contoh',
            'username' => 'santri',
            'password' => '20180101',   // gaya tanggal lahir
            'role' => 'murid',
        ]);

        // --- Pelajaran 1: Iqro ---
        $iqro = Pelajaran::create(['nama' => 'Iqro', 'urutan' => 1]);

        // Modul 1 (aktif)
        $modul1 = Modul::create([
            'pelajaran_id' => $iqro->id,
            'nama' => 'Jilid 1 - Bagian 1',
            'urutan' => 1,
            'aktif' => true,
        ]);
        $huruf1 = [['اَ', 'A'], ['بَ', 'Ba'], ['تَ', 'Ta'], ['ثَ', 'Tsa'], ['جَ', 'Ja'], ['حَ', 'Ha']];
        foreach ($huruf1 as $i => [$teks, $label]) {
            Materi::create([
                'modul_id' => $modul1->id,
                'tipe_konten' => 'teks',
                'teks_arab' => $teks,
                'label' => $label,
                'audio' => null,        // audio asli diisi nanti
                'urutan' => $i + 1,
            ]);
        }

        // Modul 2 (aktif)
        $modul2 = Modul::create([
            'pelajaran_id' => $iqro->id,
            'nama' => 'Jilid 1 - Bagian 2',
            'urutan' => 2,
            'aktif' => true,
        ]);
        $huruf2 = [['خَ', 'Kha'], ['دَ', 'Da'], ['ذَ', 'Dza'], ['رَ', 'Ra'], ['زَ', 'Za'], ['سَ', 'Sa']];
        foreach ($huruf2 as $i => [$teks, $label]) {
            Materi::create([
                'modul_id' => $modul2->id,
                'tipe_konten' => 'teks',
                'teks_arab' => $teks,
                'label' => $label,
                'audio' => null,
                'urutan' => $i + 1,
            ]);
        }

        // --- Pelajaran 2: Bahasa Arab (belum ada modul) ---
        Pelajaran::create(['nama' => 'Bahasa Arab', 'urutan' => 2]);
    }
}
