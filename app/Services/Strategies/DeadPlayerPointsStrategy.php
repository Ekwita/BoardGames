<?php

namespace App\Services\Strategies;

use App\Models\Player;
use App\Models\Result;

class DeadPlayerPointsStrategy
{
    public function calculatePoints(string $selectedPlayer, int $status, $gameData, int $playerId): void
    {
        Result::create([
            'game_id' => $gameData->id,
            'player_id' => $playerId,
            'player_name' => $selectedPlayer,
            'status' => $status,
            'total_points' => '0',
        ]);

        Player::where('player_name', $selectedPlayer)->incrementEach([
            'games' => 1,
            'deaths' => 1,
        ]);
    }
}
