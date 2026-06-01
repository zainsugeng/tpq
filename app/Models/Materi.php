<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materi extends Model
{
    protected $table = 'materi';
    protected $fillable = [
        'modul_id',
        'tipe_konten',
        'teks_arab',
        'gambar',
        'label',
        'audio',
        'urutan',
    ];

    public function modul(): BelongsTo       // materi ini milik 1 modul
    {
        return $this->belongsTo(Modul::class);
    }
}
