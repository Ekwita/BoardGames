<?php

namespace App\Strategies;

use App\Actions\PlayersResults\AlivePlayerResultCreate;
use App\Actions\PlayersStats\AlivePlayerStatsUpdate;
use App\DTOs\PlayerGameDataDTO;
use App\Enums\ArtifactType;
use App\Interfaces\PlayerPointsCalculatorInterface;
use Illuminate\Http\Request;

class AlivePlayerPointsStrategy implements PlayerPointsCalculatorInterface
{
    public function __construct(protected AlivePlayerResultCreate $alivePlayerResultCreate, protected AlivePlayerStatsUpdate $alivePlayerStatsUpdate) {}
    public function calculatePoints(PlayerGameDataDTO $playerGameDataDTO): array
    {
        $statusPoints = ($playerGameDataDTO->status == 3) ? 20 : 0;
        $gold = ($playerGameDataDTO->request->input('gold_' . $playerGameDataDTO->selectedPlayer) != null) ? $playerGameDataDTO->request->input('gold_' . $playerGameDataDTO->selectedPlayer) : 0;
        $tokens = $playerGameDataDTO->request->input('tokens_' . $playerGameDataDTO->selectedPlayer);
        $cards = $playerGameDataDTO->request->input('cards_' . $playerGameDataDTO->selectedPlayer);

        $artifactsData = $this->calculateArifactsPoints($playerGameDataDTO->request, $playerGameDataDTO->selectedPlayer, $playerGameDataDTO->playerBestArtifact);

        $totalPoints = $statusPoints + $artifactsData['totalArtifactsPoints'] + $gold + $tokens + $cards;

        $data = [
            'game_id' => $playerGameDataDTO->gameData->id,
            'player_id' => $playerGameDataDTO->playerId,
            'player_name' => $playerGameDataDTO->selectedPlayer,
            'status' => $playerGameDataDTO->status,
            'art5' => $playerGameDataDTO->request->has('art5_' . $playerGameDataDTO->selectedPlayer),
            'art7' => $playerGameDataDTO->request->has('art7_' . $playerGameDataDTO->selectedPlayer),
            'art10' => $playerGameDataDTO->request->has('art10_' . $playerGameDataDTO->selectedPlayer),
            'art12' => $playerGameDataDTO->request->has('art12_' . $playerGameDataDTO->selectedPlayer),
            'art15' => $playerGameDataDTO->request->has('art15_' . $playerGameDataDTO->selectedPlayer),
            'art17' => $playerGameDataDTO->request->has('art17_' . $playerGameDataDTO->selectedPlayer),
            'art20' => $playerGameDataDTO->request->has('art20_' . $playerGameDataDTO->selectedPlayer),
            'art25' => $playerGameDataDTO->request->has('art25_' . $playerGameDataDTO->selectedPlayer),
            'art30' => $playerGameDataDTO->request->has('art30_' . $playerGameDataDTO->selectedPlayer),
            'gold' => $gold,
            'tokens' => $tokens,
            'cards' => $cards,
            'total_points' => $totalPoints
        ];


        // Create PlayerResult
        $this->alivePlayerResultCreate->handle($data);
        $this->alivePlayerStatsUpdate->handle($data, $totalPoints);

        return [
            'totalPoints' => $totalPoints,
            'playerBestArtifact' => $artifactsData['playerBestArtifact']
        ];
    }



    // Calculate points for artifacts
    private function calculateArifactsPoints(Request $request, string $selectedPlayer, $playerBestArtifact): array
    {
        $playerBestArtifact = 0;
        $totalArtifactsPoints = 0;
        foreach (ArtifactType::getAllArtifacts() as $artifactPoints) {
            if ($request->has('art' . $artifactPoints->value . '_' . $selectedPlayer)) {
                $totalArtifactsPoints += $artifactPoints->value;
                if ($artifactPoints->value > $playerBestArtifact) {
                    $playerBestArtifact = $artifactPoints->value;
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
