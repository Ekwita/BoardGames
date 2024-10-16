<?php

namespace App\Services;

use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\PlayerGameDataDTO;
use App\Interfaces\PlayerPointsCalculatorInterface;
use App\Interfaces\PlayerPointsServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PlayerPointsService implements PlayerPointsServiceInterface
{

    // public function __construct(protected PlayerPointsCalculatorInterface ){}
    public function calculate(Request $request, Collection $selectedPlayers, GameDataDTO $gameData): ?string
    {
        $bestScore = 0;
        $bestArticaft = 0;
        $bestPlayer = '';


        foreach ($selectedPlayers as $selectedPlayerResult) {
            $playerId = $selectedPlayerResult->playerId;
            $selectedPlayer = $selectedPlayerResult->playerName;

            $status = $request->input('status_' . $selectedPlayer);

            $totalPoints = 0;
            $playerBestArtifact = 0;


            $playerGameDataDto = new PlayerGameDataDTO($request, $selectedPlayer, $status, $gameData, $playerId, $playerBestArtifact);

            $pointsResult = app()->make(PlayerPointsCalculatorInterface::class, ['type' => $status])->calculatePoints($playerGameDataDto);

            $totalPoints = $pointsResult['totalPoints'];
            $playerBestArtifact = $pointsResult['playerBestArtifact'];


            if ($totalPoints > $bestScore || $totalPoints == $bestScore && $playerBestArtifact > $bestArticaft) {
                $bestScore = $totalPoints;
                $bestArticaft = $playerBestArtifact;
                $bestPlayer = $selectedPlayer;
            }
        }
        return $bestPlayer;
    }
}
