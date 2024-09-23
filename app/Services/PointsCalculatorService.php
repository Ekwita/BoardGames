<?php

namespace App\Services;

use App\Actions\SetWinner;
use App\DTOs\GamesListDTO;
use App\Interfaces\PlayerPointsCalculatorInterface;
use App\Interfaces\PointsCalculatorInterface;
use App\Models\Game;
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

            $totalPoints = 0;
            $playerBestArtifact = 0;

            $pointsCalculator = app()->make(PlayerPointsCalculatorInterface::class, ['type' => $status]);
            $pointsResult = $pointsCalculator->calculatePoints($request, $selectedPlayer, $status, $gameData, $playerId, $playerBestArtifact);

            $totalPoints = $pointsResult['totalPoints'];
            $playerBestArtifact = $pointsResult['playerBestArtifact'];


            if ($totalPoints > $bestScore || $totalPoints == $bestScore && $playerBestArtifact > $bestArticaft) {
                $bestScore = $totalPoints;
                $bestArticaft = $playerBestArtifact;
                $bestPlayer = $selectedPlayer;
            }
        }
        if ($bestPlayer != null) {
            app(SetWinner::class)->handle($gameData, $bestPlayer);
        }


        $resultData = $this->getGameResultFromDatabase($gameData);

        $request->session()->flush();

        return $resultData;
    }

    //PRIVATE METHODS

    //Summary of createResultData
    private function getGameResultFromDatabase($gameData): array
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
