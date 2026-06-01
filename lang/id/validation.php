<?php

return [
    'required' => 'Kolom :attribute wajib diisi.',
    'string'   => 'Kolom :attribute harus berupa teks.',
    'integer'  => 'Kolom :attribute harus berupa angka.',
    'boolean'  => 'Kolom :attribute harus bernilai ya/tidak.',
    'unique'   => ':attribute sudah dipakai, silakan pilih yang lain.',
    'exists'   => ':attribute yang dipilih tidak valid.',
    'in'       => ':attribute yang dipilih tidak valid.',
    'image'    => 'File :attribute harus berupa gambar.',
    'mimes'    => 'File :attribute harus berformat: :values.',
    'min'      => [
        'string'  => 'Kolom :attribute minimal :min karakter.',
        'numeric' => 'Kolom :attribute minimal :min.',
    ],
    'max'      => [
        'string'  => 'Kolom :attribute maksimal :max karakter.',
        'file'    => 'File :attribute maksimal :max kilobyte.',
        'numeric' => 'Kolom :attribute maksimal :max.',
    ],

    // nama kolom biar enak dibaca (ganti :attribute)
    'attributes' => [
        'nama'         => 'Nama',
        'username'     => 'Username',
        'password'     => 'Password',
        'pelajaran_id' => 'Pelajaran',
        'modul_id'     => 'Modul',
        'tipe_konten'  => 'Jenis konten',
        'teks_arab'    => 'Teks Arab',
        'label'        => 'Label',
        'gambar'       => 'Gambar',
        'audio'        => 'Audio',
        'urutan'       => 'Urutan',
    ],

    'custom' => [],
];
