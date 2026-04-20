<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vote extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'candidate_id'];

    /**
     * Relasi balik: Satu suara dimiliki oleh satu User (pemilih)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi balik: Satu suara ditujukan untuk satu Kandidat
     */
    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}