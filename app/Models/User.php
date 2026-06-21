<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'nama_ortu',
        'jenis_kelamin',
        'tanggal_lahir',
        'username',
        'password',
        'role',
        'jumlah_streak',
        'tanggal_terakhir_aktif',
        'guru_id',                 // <- baru
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tanggal_terakhir_aktif' => 'date',
        ];
    }

    // 1 murid punya banyak progres
    public function progres(): HasMany
    {
        return $this->hasMany(Progres::class, 'murid_id');
    }

    // --- Relasi multi-guru (baru) ---

    // Murid -> guru/admin pemiliknya
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Guru/admin -> murid-murid yang dia pegang
    public function murid(): HasMany
    {
        return $this->hasMany(User::class, 'guru_id');
    }

    // Guru/admin -> pelajaran-pelajaran miliknya
    public function pelajaran(): HasMany
    {
        return $this->hasMany(Pelajaran::class, 'guru_id');
    }
}
