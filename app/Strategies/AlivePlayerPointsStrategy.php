<?php

namespace App\Strategies;

use App\Actions\PlayersResults\AlivePlayerResultCreate;
use App\Actions\PlayersStats\AlivePlayerStatsUpdate;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\PlayerGameDataDTO;
use App\Enums\ArtifactType;
use App\Interfaces\PlayerPointsCalculatorInterface;
use Illuminate\Http\Request;

class AlivePlayerPointsStrategy implements PlayerPointsCalculatorInterface
{
    public function __construct(protected AlivePlayerResultCreate $alivePlayerResultCreate, protected AlivePlayerStatsUpdate $alivePlayerStatsUpdate) {}

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
        $this->alivePlayerResultCreate->handle($dto);
        $this->alivePlayerStatsUpdate->handle($dto);

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
