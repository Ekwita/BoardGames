<?php

namespace App\Strategies;

use App\Actions\PlayersResults\DeadPlayerResultCreate;
use App\Actions\PlayersStats\DeadPlayerStatsUpdate;

class DeadPlayerPointsStrategy
{
    public function __construct(private DeadPlayerResultCreate $deadPlayerResultCreate, private DeadPlayerStatsUpdate $deadPlayerStatsUpdate) {}
    public function calculatePoints(string $selectedPlayer, int $status, $gameData, int $playerId): void
    {
        $data = [
            'game_id' => $gameData->id,
            'player_id' => $playerId,
            'player_name' => $selectedPlayer,
            'status' => $status,
            'total_points' => 0
        ];

        $this->deadPlayerResultCreate->handle($data);
        $this->deadPlayerStatsUpdate->handle($data);
    }
}
