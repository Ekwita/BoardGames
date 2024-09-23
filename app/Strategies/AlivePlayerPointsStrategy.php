<?php

namespace App\Strategies;

use App\Actions\PlayersResults\AlivePlayerResultCreate;
use App\Actions\PlayersStats\AlivePlayerStatsUpdate;
use App\Enums\ArtifactType;
use App\Interfaces\PlayerPointsCalculatorInterface;
use Illuminate\Http\Request;

class AlivePlayerPointsStrategy implements PlayerPointsCalculatorInterface
{
    public function __construct(protected AlivePlayerResultCreate $alivePlayerResultCreate, protected AlivePlayerStatsUpdate $alivePlayerStatsUpdate) {}
    public function calculatePoints(Request $request, string $selectedPlayer, $status, $gameData, $playerId, $playerBestArtifact): array
    {
        $statusPoints = ($status == 3) ? 20 : 0;
        $gold = ($request->input('gold_' . $selectedPlayer) != null) ? $request->input('gold_' . $selectedPlayer) : 0;
        $tokens = $request->input('tokens_' . $selectedPlayer);
        $cards = $request->input('cards_' . $selectedPlayer);

        $artifactsData = $this->calculateArifactsPoints($request, $selectedPlayer, $playerBestArtifact);

        $totalPoints = $statusPoints + $artifactsData['totalArtifactsPoints'] + $gold + $tokens + $cards;

        $data = [
            'game_id' => $gameData->id,
            'player_id' => $playerId,
            'player_name' => $selectedPlayer,
            'status' => $status,
            'art5' => $request->has('art5_' . $selectedPlayer),
            'art7' => $request->has('art7_' . $selectedPlayer),
            'art10' => $request->has('art10_' . $selectedPlayer),
            'art12' => $request->has('art12_' . $selectedPlayer),
            'art15' => $request->has('art15_' . $selectedPlayer),
            'art17' => $request->has('art17_' . $selectedPlayer),
            'art20' => $request->has('art20_' . $selectedPlayer),
            'art25' => $request->has('art25_' . $selectedPlayer),
            'art30' => $request->has('art30_' . $selectedPlayer),
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
