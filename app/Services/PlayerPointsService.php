<?php

namespace App\Services;

use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\NewGameParams\PlayerPointsComparisonDTO;
use App\Interfaces\PlayerPointsCalculatorInterface;
use App\Interfaces\PlayerPointsServiceInterface;

class PlayerPointsService implements PlayerPointsServiceInterface
{
    public function calculate(OnePlayerResultDTO $playerResultDto, PlayerPointsComparisonDTO $playerPointsDto): PlayerPointsComparisonDTO
    {
        $totalPoints = 0;
        $playerBestArtifact = 0;

        $pointsResult = app()->make(PlayerPointsCalculatorInterface::class, ['type' => $playerResultDto->status])->calculatePoints($playerResultDto);

        $totalPoints = $pointsResult['totalPoints'];
        $playerBestArtifact = $pointsResult['playerBestArtifact'];

        return $this->setBestPlayer($totalPoints, $playerBestArtifact, $playerResultDto, $playerPointsDto);
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
