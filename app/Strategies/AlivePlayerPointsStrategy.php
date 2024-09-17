<?php

namespace App\Strategies;

use App\Actions\PlayersResults\AlivePlayerResultCreate;
use App\Actions\PlayersStats\AlivePlayerStatsUpdate;
use App\Enums\ArtifactType;
use Illuminate\Http\Request;

class AlivePlayerPointsStrategy
{
    public function __construct(private AlivePlayerResultCreate $alivePlayerResultCreate, private AlivePlayerStatsUpdate $alivePlayerStatsUpdate) {}
    public function calculatePoints(Request $request, string $selectedPlayer, $status, $gameData, $playerId, $playerBestArtifact)
    {
        $statusPoints = ($status == 3) ? 20 : 0;
        $gold = ($request->input('gold_' . $selectedPlayer) != null) ? $request->input('gold_' . $selectedPlayer) : 0;
        $tokens = $request->input('tokens_' . $selectedPlayer);
        $cards = $request->input('cards_' . $selectedPlayer);

        $totalArtifactsPoints = $this->calculateArifactsPoints($request, $selectedPlayer, $playerBestArtifact);

        $totalPoints = $statusPoints + $totalArtifactsPoints + $gold + $tokens + $cards;

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
            'playerBestArtifact' => $playerBestArtifact
        ];
    }



    // Calculate points for artifacts
    private function calculateArifactsPoints(Request $request, string $selectedPlayer, $playerBestArtifact): int
    {
        $totalArtifactsPoints = 0;
        foreach (ArtifactType::getAllArtifacts() as $artifactPoints) {
            if ($request->has('art' . $artifactPoints->value . '_' . $selectedPlayer)) {
                $totalArtifactsPoints += $artifactPoints->value;
                if ($artifactPoints > $playerBestArtifact) {
                    $playerBestArtifact = $artifactPoints;
                }
            }
        }

        return $totalArtifactsPoints;
    }
}
