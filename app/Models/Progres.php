<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Progres extends Model
{
    protected $table = 'progres';
    protected $fillable = ['murid_id', 'modul_id', 'selesai_pada'];
    protected $casts = ['selesai_pada' => 'datetime'];

    public function murid(): BelongsTo       // progres ini milik 1 murid
    {
        return $this->belongsTo(User::class, 'murid_id');
    }

    public function modul(): BelongsTo       // progres ini untuk 1 modul
    {
        return $this->belongsTo(Modul::class);
    }
}
