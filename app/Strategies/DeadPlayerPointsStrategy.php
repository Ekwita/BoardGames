<?php

namespace App\Strategies;

use App\Actions\PlayersResults\DeadPlayerResultCreate;
use App\Actions\PlayersStats\DeadPlayerStatsUpdate;
use App\DTOs\PlayerGameDataDTO;
use App\Interfaces\PlayerPointsCalculatorInterface;

class DeadPlayerPointsStrategy implements PlayerPointsCalculatorInterface
{
    public function __construct(protected DeadPlayerResultCreate $deadPlayerResultCreate, protected DeadPlayerStatsUpdate $deadPlayerStatsUpdate) {}
    
    public function calculatePoints(PlayerGameDataDTO $playerGameDataDTO): array
    {
        $data = [
            'game_id' => $playerGameDataDTO->gameData->id,
            'player_id' => $playerGameDataDTO->playerId,
            'player_name' => $playerGameDataDTO->selectedPlayer,
            'status' => $playerGameDataDTO->status,
            'total_points' => 0
        ];

        $this->deadPlayerResultCreate->handle($data);
        $this->deadPlayerStatsUpdate->handle($data);

        return [
            'totalPoints' => 0,
            'playerBestArtifact' => null
        ];
    }
}
