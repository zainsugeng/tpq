<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelajaran extends Model
{
    protected $table = 'pelajaran';
    protected $fillable = ['nama', 'urutan'];

    // 1 pelajaran punya banyak modul
    public function modul(): HasMany
    {
        return $this->hasMany(Modul::class);
    }
}
