<?php

namespace App\Services;

use App\DTOs\AllPlayersResultsDTO;
use App\DTOs\GameDataDTO;
use App\DTOs\GamesListDTO;
use App\DTOs\OnePlayerResultDTO;
use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GameService
{

    /**
     * Get list of all games and return data to controller
     * @return array
     */
    public function getGamesList(): array
    {

        $allGameIds = Game::pluck('id');

        if ($allGameIds->isEmpty()) {
            return ['games' => null];
        }

        $games = Game::whereIn('id', $allGameIds)->orderByDesc('id')->paginate(1);
        $gamesStatiscitcs = Result::whereIn('game_id', $allGameIds)->get()->groupBy('game_id');

        $gameDetails = $games->map(function ($game) use ($gamesStatiscitcs) {
            $gameId = $game->id;
            $winner = $game->winner;
            $statistics = $gamesStatiscitcs->get($gameId, collect());
            $createdAt = $game->created_at ? $game->created_at->format('d-m-Y H:i:s') : null;

            return new GamesListDTO(
                gameId: $gameId,
                winner: $winner,
                statistics: $statistics,
                createdAt: $createdAt
            );
        });

        $paginatedGames = new \Illuminate\Pagination\LengthAwarePaginator(
            $gameDetails,
            $games->total(),
            $games->perPage(),
            $games->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return ['games' => $paginatedGames];
    }



    /**
     * Create new GameDataDTO object
     * Put data to session
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function createData(Request $request): void
    {
        $gameId = $this->getLastGame();

        $gamePlayers = $this->selectPlayersInGame($request);
        // dd($gamePlayers);
        $playersResultCollection = new Collection();
        foreach ($gamePlayers as $player) {
            $playerResult = new OnePlayerResultDTO(
                playerName: $player
            );
            $playersResultCollection->push($playerResult);
        }
        $allPlayersResults = new AllPlayersResultsDTO($playersResultCollection);


        $gameData = new GameDataDTO($gameId, allPlayersResults: $allPlayersResults);
        // dd($gameData);
        $request->session()->put('gameData', $gameData);
    }

    /**
     * Get players list from session
     */
    public function getPlayersFromSession(): Collection
    {
        $gameData = session()->get('gameData');

        $players = $gameData->allPlayersResults->playersResults->map(function ($playerResult) {
            return $playerResult->playerName;
        });

        return $players;
    }

    /**
     * Calculate points for each player
     * @return array
     */
    public function pointsCalculator(Request $request)
    {
        $gameData = $request->session()->get('gameData');

        $bestScore = 0;
        $bestArticaft = 0;
        $bestPlayer = '';

        $this->createGame();

        foreach ($gameData->players as $player) {
            $playerId = Player::where('player_name', $player)->value('id');
            $status = $request->input('status_' . $player);

            $statusPoints = ($status == 3) ? 20 : 0;
            $playerBestArtifact = 0;
            $totalPoints = 0;

            if ($status != 1) {

                $gold = ($request->input('gold_' . $player) != null) ? $request->input('gold_' . $player) : 0;
                $tokens = $request->input('tokens_' . $player);
                $cards = $request->input('cards_' . $player);


                $artifacts = [
                    'art5_' . $player => 5,
                    'art7_' . $player => 7,
                    'art10_' . $player => 10,
                    'art12_' . $player => 12,
                    'art15_' . $player => 15,
                    'art17_' . $player => 17,
                    'art20_' . $player => 20,
                    'art25_' . $player => 25,
                    'art30_' . $player => 30,
                ];

                $totalArtifactsPoints = 0;

                foreach ($artifacts as $artifactName => $artifactPoints) {
                    if ($request->has($artifactName)) {
                        $totalArtifactsPoints += $artifactPoints;
                        if ($artifactPoints > $playerBestArtifact) {
                            $playerBestArtifact = $artifactPoints;
                        }
                    }
                }

                $totalPoints = $statusPoints + $totalArtifactsPoints + $gold + $tokens + $cards;

                $data = [
                    'game_id' => $gameData->id,
                    'player_id' => $playerId,
                    'player' => $player,
                    'status' => $status,
                    'art5' => $request->has('art5_' . $player),
                    'art7' => $request->has('art7_' . $player),
                    'art10' => $request->has('art10_' . $player),
                    'art12' => $request->has('art12_' . $player),
                    'art15' => $request->has('art15_' . $player),
                    'art17' => $request->has('art17_' . $player),
                    'art20' => $request->has('art20_' . $player),
                    'art25' => $request->has('art25_' . $player),
                    'art30' => $request->has('art30_' . $player),
                    'gold' => $gold,
                    'tokens' => $tokens,
                    'cards' => $cards,
                    'totalPoints' => $totalPoints
                ];


                Result::create([
                    'game_id' => $gameData->id,
                    'player_id' => $playerId,
                    'player_name' => $data['player'],
                    'status' => $data['status'],
                    'art5' => $request->has('art5_' . $data['player']),
                    'art7' => $request->has('art7_' . $data['player']),
                    'art10' => $request->has('art10_' . $data['player']),
                    'art12' => $request->has('art12_' . $data['player']),
                    'art15' => $request->has('art15_' . $data['player']),
                    'art17' => $request->has('art17_' . $data['player']),
                    'art20' => $request->has('art20_' . $data['player']),
                    'art25' => $request->has('art25_' . $data['player']),
                    'art30' => $request->has('art30_' . $data['player']),
                    'gold' => $data['gold'],
                    'tokens' => $data['tokens'],
                    'cards' => $data['cards'],
                    'total_points' => $data['totalPoints'],
                ]);

                $playerToUpdate = Player::where('player_name', $player);
                $playerToUpdate->incrementEach([
                    'games' => 1,
                    'totalgold' => $gold,
                    'art5' => $request->has('art5_' . $player) ? 1 : 0,
                    'art7' => $request->has('art7_' . $player) ? 1 : 0,
                    'art10' => $request->has('art10_' . $player) ? 1 : 0,
                    'art12' => $request->has('art12_' . $player) ? 1 : 0,
                    'art15' => $request->has('art15_' . $player) ? 1 : 0,
                    'art17' => $request->has('art17_' . $player) ? 1 : 0,
                    'art20' => $request->has('art20_' . $player) ? 1 : 0,
                    'art25' => $request->has('art25_' . $player) ? 1 : 0,
                    'art30' => $request->has('art30_' . $player) ? 1 : 0,
                ]);
            } else {
                Result::create([
                    'game_id' => $gameData->id,
                    'player_id' => $playerId,
                    'player_name' => $player,
                    'status' => $status,
                    'total_points' => '0',
                ]);

                Player::where('player_name', $player)->incrementEach([
                    'games' => 1,
                    'deaths' => 1,
                ]);
            }

            if ($totalPoints > $bestScore || $totalPoints == $bestScore && $playerBestArtifact > $bestArticaft) {
                $bestScore = $totalPoints;
                $bestArticaft = $playerBestArtifact;
                $bestPlayer = $player;
            }
            if ($totalPoints > Player::where('player_name', $player)->value('best')) {
                Player::where('player_name', $player)->update(['best' => $totalPoints]);
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

    /**
     * Create id for new game based on id of the last game
     * @return int
     */
    private function getLastGame(): int
    {
        $latestGame = Game::latest()->first();
        $lastGameId = $latestGame ? $latestGame->id : 0;
        $newGameId = $lastGameId + 1;

        return $newGameId;
    }

    /**
     * Summary of selectPlayersInGame
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function selectPlayersInGame(Request $request): array
    {
        $selectedPlayers = [];

        for ($playerNumber = 1; $playerNumber <= 6; $playerNumber++) {
            $key = 'player' . $playerNumber;
            $inputValue = $request->input($key);

            if ($inputValue !== null) {
                $selectedPlayers[$key] = $inputValue;
            }
        }

        return $selectedPlayers;
    }

    /**
     * Create and add new game to database
     * Create Game as DTO object
     * @return GamesListDTO
     */
    private function createGame(): GamesListDTO
    {
        $game = Game::create([]);
        return new GamesListDTO($game->id, $game->winner, null, $game->created_at);
    }

    private function createResultData($gameData)
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
}
