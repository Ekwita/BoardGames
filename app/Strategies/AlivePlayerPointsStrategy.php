<?php

namespace App\Strategies;

use App\Actions\Interfaces\PlayerResultCreateInterface;
use App\Actions\Interfaces\PlayerStatsUpdateInterface;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Enums\ArtifactType;
use App\Interfaces\PlayerPointsCalculatorInterface;
use App\Interfaces\PlayerStatusStrategyInterface;

class AlivePlayerPointsStrategy implements PlayerPointsCalculatorInterface, PlayerStatusStrategyInterface
{
    public function __construct(protected PlayerResultCreateInterface $playerResultCreate, protected PlayerStatsUpdateInterface $playerStatsUpdate) {}

    public function isSatisfiedBy($playerStatus): bool
    {
        return $playerStatus !== 1;
    }

    public function calculatePoints(OnePlayerResultDTO $dto): array
    {
        $statusPoints = $this->calculateStatusPoints($dto->status);

        $gold = ($dto->gold != null) ? $dto->gold : 0;
        $tokens = $dto->tokens;
        $cards = $dto->cards;


        $artifactsData = $this->calculateArifactsPoints($dto);


        $totalPoints = $statusPoints + $artifactsData['totalArtifactsPoints'] + $gold + $tokens + $cards;

        //Update DTO object
        $dto->totalPoints = $totalPoints;

        // Create PlayerResult
        $this->playerResultCreate->handle($dto);
        $this->playerStatsUpdate->handle($dto);

        return [
            'totalPoints' => $totalPoints,
            'playerBestArtifact' => $artifactsData['playerBestArtifact']
        ];
    }

    //PRIVATE METHODS

    private function calculateStatusPoints(int $status): int
    {
        return ($status == 3) ? 20 : 0;
    }

    // Calculate points for artifacts
    private function calculateArifactsPoints(OnePlayerResultDTO $dto): array
    {
        $playerBestArtifact = 0;
        $totalArtifactsPoints = 0;

        foreach (ArtifactType::getAllArtifacts() as $artifactType) {
            $propertyName = 'art' . $artifactType->value;

            if (($dto->{$propertyName}) === true) {
                $totalArtifactsPoints += $artifactType->value;

                if ($artifactType->value > $playerBestArtifact) {
                    $playerBestArtifact = $artifactType->value;
                }
            }
        }

        $artifactsData = [
            'totalArtifactsPoints' => $totalArtifactsPoints,
            'playerBestArtifact' => $playerBestArtifact
        ];

        return $artifactsData;
    }
}
