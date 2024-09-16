<?php

namespace App\Services;

use App\DTOs\GamesListDTO;
use App\Enums\ArtifactType;
use App\Interfaces\PointsCalculatorInterface;
use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use Illuminate\Http\Request;

class PointsCalculatorService implements PointsCalculatorInterface
{
    /**
     * Calculate points for each player
     */
    public function pointsCalculator(Request $request): array
    {
        $gameData = session()->get('gameData');
        $selectedPlayers = $gameData->allPlayersResults->playersResults;

        $bestScore = 0;
        $bestArticaft = 0;
        $bestPlayer = '';

        $this->createGame();

        foreach ($selectedPlayers as $selectedPlayerResult) {
            $playerId = $selectedPlayerResult->playerId;
            $selectedPlayer = $selectedPlayerResult->playerName;

            $status = $request->input('status_' . $selectedPlayer);

            $statusPoints = ($status == 3) ? 20 : 0;
            $playerBestArtifact = 0;
            $totalPoints = 0;

            if ($status != 1) {

                $gold = ($request->input('gold_' . $selectedPlayer) != null) ? $request->input('gold_' . $selectedPlayer) : 0;
                $tokens = $request->input('tokens_' . $selectedPlayer);
                $cards = $request->input('cards_' . $selectedPlayer);

                $totalArtifactsPoints = $this->calculateArifatsPoints($request, $selectedPlayer, $playerBestArtifact);

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

                Result::create($data);

                $playerToUpdate = Player::where('player_name', $selectedPlayer);
                $playerToUpdate->incrementEach([
                    'games' => 1,
                    'totalgold' => $data['gold'],
                    'art5' => $data['art5'] ? 1 : 0,
                    'art7' => $data['art7'] ? 1 : 0,
                    'art10' => $data['art10'] ? 1 : 0,
                    'art12' => $data['art12'] ? 1 : 0,
                    'art15' => $data['art15'] ? 1 : 0,
                    'art17' => $data['art17'] ? 1 : 0,
                    'art20' => $data['art20'] ? 1 : 0,
                    'art25' => $data['art25'] ? 1 : 0,
                    'art30' => $data['art30'] ? 1 : 0,
                ]);
            } else {
                Result::create([
                    'game_id' => $gameData->id,
                    'player_id' => $playerId,
                    'player_name' => $selectedPlayer,
                    'status' => $status,
                    'total_points' => '0',
                ]);

                Player::where('player_name', $selectedPlayer)->incrementEach([
                    'games' => 1,
                    'deaths' => 1,
                ]);
            }

            if ($totalPoints > $bestScore || $totalPoints == $bestScore && $playerBestArtifact > $bestArticaft) {
                $bestScore = $totalPoints;
                $bestArticaft = $playerBestArtifact;
                $bestPlayer = $selectedPlayer;
            }
            if ($totalPoints > Player::where('player_name', $selectedPlayer)->value('best')) {
                Player::where('player_name', $selectedPlayer)->update(['best' => $totalPoints]);
            }
        }
        if ($bestPlayer != null) {
            Game::where('id', $gameData->id)->update(['winner' => $bestPlayer]);
            Player::where('player_name', $bestPlayer)->increment('wins', 1);
        }


        $resultData = $this->createResultData($gameData);


        $request->session()->flush();
        return $resultData;
    }

    //PRIVATE FUNCTIONS

    // Calculate points for artifacts
    private function calculateArifatsPoints(Request $request, string $selectedPlayer, $playerBestArtifact): int
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

    //Summary of createResultData
    private function createResultData($gameData): array
    {
        $results = Result::where('game_id', $gameData->id)
            ->orderByDesc('total_points')
            ->orderByDesc('art30')
            ->orderByDesc('art25')
            ->orderByDesc('art20')
            ->orderByDesc('art17')
            ->orderByDesc('art15')
            ->orderByDesc('art12')
            ->orderByDesc('art10')
            ->orderByDesc('art7')
            ->orderByDesc('art5')
            ->get();

        $winner = Game::where('id', $gameData->id)->value('winner');

        $resultData = [
            'results' => $results,
            'winner' => $winner
        ];

        return $resultData;
    }

    // Create and add new game to database & create Game as DTO object
    private function createGame(): GamesListDTO
    {
        $game = Game::create();
        return new GamesListDTO($game->id, $game->created_at, $game->winner, null,);
    }
}
