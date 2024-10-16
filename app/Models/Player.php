<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_name',
        'user_id',
        'games',
        'wins',
        'deaths',
        'best',
        'average',
        'totalGold',
        'art5',
        'art7',
        'art10',
        'art12',
        'art15',
        'art17',
        'art20',
        'art25',
        'art30'
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
