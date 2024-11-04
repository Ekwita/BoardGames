<?php

namespace App\Models;

use App\DTOs\Players\PlayerStatisticDTO;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Player extends Model
{
    use HasFactory, HasUuids;

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

    public function mapToDto(): PlayerStatisticDTO
    {
        return new PlayerStatisticDTO(
            id: $this->id,
            user_id: $this->user_id,
            player_name: $this->player_name,
            games: $this->games,
            wins: $this->wins,
            deaths: $this->deaths,
            best: $this->best,
            average: $this->average,
            totalGold: $this->totalGold,
            art5: $this->art5,
            art7: $this->art7,
            art10: $this->art10,
            art12: $this->art12,
            art15: $this->art15,
            art17: $this->art17,
            art20: $this->art20,
            art25: $this->art25,
            art30: $this->art30
        );
    }
}
