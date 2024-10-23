<?php

namespace App\Strategies;

use App\Actions\PlayersResults\DeadPlayerResultCreate;
use App\Actions\PlayersStats\DeadPlayerStatsUpdate;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Interfaces\PlayerPointsCalculatorInterface;

class DeadPlayerPointsStrategy implements PlayerPointsCalculatorInterface
{
    public function __construct(protected DeadPlayerResultCreate $deadPlayerResultCreate, protected DeadPlayerStatsUpdate $deadPlayerStatsUpdate) {}

    public function calculatePoints(OnePlayerResultDTO $dto): array
    {
        $this->deadPlayerResultCreate->handle($dto);
        $this->deadPlayerStatsUpdate->handle($dto);

        return [
            'totalPoints' => 0,
            'playerBestArtifact' => 0
        ];
    }
}
