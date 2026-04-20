<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidate extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi manual
    protected $fillable = ['name', 'nim', 'angkatan', 'photo'];

    /**
     * Relasi: Satu kandidat bisa memiliki banyak suara (votes)
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }
}