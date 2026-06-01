<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'role',
        'jumlah_streak',
        'tanggal_terakhir_aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',                 // password otomatis di-hash
            'tanggal_terakhir_aktif' => 'date',
        ];
    }

    // 1 murid punya banyak progres
    public function progres(): HasMany
    {
        return $this->hasMany(Progres::class, 'murid_id');
    }
}
