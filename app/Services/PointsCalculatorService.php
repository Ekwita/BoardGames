<?php

namespace App\Services;

use App\DTOs\GamesListDTO;
use App\Enums\ArtifactType;
use App\Interfaces\PointsCalculatorInterface;
use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use App\Services\Strategies\AlivePlayerPointsStrategy;
use App\Services\Strategies\DeadPlayerPointsStrategy;
use Illuminate\Http\Request;

class PointsCalculatorService implements PointsCalculatorInterface
{

    public function __construct(private AlivePlayerPointsStrategy $alivePlayerPointsStrategy, private DeadPlayerPointsStrategy $deadPlayerPointsStrategy) {}
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

            $playerBestArtifact = 0;
            $totalPoints = 0;
            if ($status != 1) {
                $pointsResult = $this->alivePlayerPointsStrategy->calculatePoints($request, $selectedPlayer, $status, $gameData, $playerId, $playerBestArtifact);
                $totalPoints = $pointsResult['totalPoints'];
                $playerBestArtifact = $pointsResult['bestArtifact'];
            } else {
                $this->deadPlayerPointsStrategy->calculatePoints($selectedPlayer, $status, $gameData, $playerId);
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
