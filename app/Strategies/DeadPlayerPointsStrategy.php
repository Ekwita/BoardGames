<?php

namespace App\Strategies;

use App\Actions\Interfaces\PlayerResultCreateInterface;
use App\Actions\Interfaces\PlayerStatsUpdateInterface;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Interfaces\PlayerPointsCalculatorInterface;
use App\Interfaces\PlayerStatusStrategyInterface;

class DeadPlayerPointsStrategy implements PlayerPointsCalculatorInterface, PlayerStatusStrategyInterface
{
    public function __construct(protected PlayerResultCreateInterface $playerResultCreate, protected PlayerStatsUpdateInterface $playerStatsUpdate) {}

    public function isSatisfiedBy(int $playerStatus): bool
    {
        return $playerStatus == 1;
    }

    public function calculatePoints(OnePlayerResultDTO $dto): array
    {
        $this->playerResultCreate->handle($dto);
        $this->playerStatsUpdate->handle($dto);

        return [
            'totalPoints' => 0,
            'playerBestArtifact' => 0
        ];
    }
}
