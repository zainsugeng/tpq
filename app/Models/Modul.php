<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Modul extends Model
{
    protected $table = 'modul';
    protected $fillable = ['pelajaran_id', 'nama', 'urutan', 'aktif'];
    protected $casts = ['aktif' => 'boolean'];

    public function pelajaran(): BelongsTo   // modul ini milik 1 pelajaran
    {
        return $this->belongsTo(Pelajaran::class);
    }

    public function materi(): HasMany        // 1 modul punya banyak materi
    {
        return $this->hasMany(Materi::class);
    }

    public function progres(): HasMany       // 1 modul punya banyak progres
    {
        return $this->hasMany(Progres::class);
    }
}
