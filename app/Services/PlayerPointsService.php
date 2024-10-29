<?php

namespace App\Services;

use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\NewGameParams\PlayerPointsComparisonDTO;
use App\Enums\PlayerStatusStrategyEnum;
use App\Interfaces\PlayerPointsServiceInterface;
use App\Interfaces\PlayerStatusStrategyInterface;
use App\Strategies\AlivePlayerPointsStrategy;
use App\Strategies\DeadPlayerPointsStrategy;
use Illuminate\Contracts\Foundation\Application;

class PlayerPointsService implements PlayerPointsServiceInterface
{
    public function __construct(protected Application $app) {}

    public function calculate(OnePlayerResultDTO $playerResultDto, PlayerPointsComparisonDTO $playerPointsDto): PlayerPointsComparisonDTO
    {
        $totalPoints = 0;
        $playerBestArtifact = 0;

        $selectedStrategy = $this->chooseStrategyByStatus($playerResultDto->status);

        $pointsResult = $selectedStrategy->calculatePoints($playerResultDto);

        $totalPoints = $pointsResult['totalPoints'];
        $playerBestArtifact = $pointsResult['playerBestArtifact'];

        return $this->setBestPlayer($totalPoints, $playerBestArtifact, $playerResultDto, $playerPointsDto);
    }

    private function chooseStrategyByStatus(int $playerStatus): ?PlayerStatusStrategyInterface
    {
        $playerPointsStrategy = null;

        foreach (PlayerStatusStrategyEnum::cases() as $strategy) {
            $playerPointsStrategy = $strategy->make();
            if ($playerPointsStrategy->isSatisfiedBy($playerStatus)) {
                return $playerPointsStrategy;
            }
        };

        return null;
    }

    private function setBestPlayer(int $totalPoints, int $playerBestArtifact, OnePlayerResultDTO $dto, PlayerPointsComparisonDTO $playerPointsDto): ?PlayerPointsComparisonDTO
    {
        if ($totalPoints > $playerPointsDto->bestScore || $totalPoints == $playerPointsDto->bestScore && $playerBestArtifact > $playerPointsDto->bestArtifact) {
            $playerPointsDto->bestScore = $totalPoints;
            $playerPointsDto->bestArtifact = $playerBestArtifact;
            $playerPointsDto->bestPlayer = $dto->playerName;
        }

        return $playerPointsDto;
    }
}
