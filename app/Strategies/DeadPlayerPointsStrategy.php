<?php

namespace App\Strategies;

use App\Actions\PlayersResults\DeadPlayerResultCreate;
use App\Actions\PlayersStats\DeadPlayerStatsUpdate;
use App\Interfaces\PlayerPointsCalculatorInterface;
use Illuminate\Http\Request;

class DeadPlayerPointsStrategy implements PlayerPointsCalculatorInterface
{
    public function __construct(protected DeadPlayerResultCreate $deadPlayerResultCreate, protected DeadPlayerStatsUpdate $deadPlayerStatsUpdate) {}
    
    public function calculatePoints(Request $request, string $selectedPlayer, $status, $gameData, $playerId, $playerBestArtifact): array
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

        return [
            'totalPoints' => 0,
            'playerBestArtifact' => null
        ];
    }
}
