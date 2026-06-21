<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelajaran extends Model
{
    protected $table = 'pelajaran';
    protected $fillable = ['nama', 'subjudul', 'warna', 'urutan', 'guru_id']; // guru_id baru

    // 1 pelajaran punya banyak modul
    public function modul(): HasMany
    {
        return $this->hasMany(Modul::class);
    }

    // pelajaran -> guru/admin pemiliknya
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
}
