<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_id',
        'player_id',
        'player_name',
        'status',
        'art5',
        'art7',
        'art10',
        'art12',
        'art15',
        'art17',
        'art20',
        'art25',
        'art30',
        'gold',
        'tokens',
        'cards',
        'total_points'
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
