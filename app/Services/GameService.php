<?php

namespace App\Services;

use App\DTOs\AllPlayersResultsDTO;
use App\DTOs\GameDataDTO;
use App\DTOs\GamesListDTO;
use App\DTOs\OnePlayerResultDTO;
use App\Enums\ArtifactType;
use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class GameService
{
    /**
     * Create new GameDataDTO object
     * Put data to session
     */
    public function createData(Request $request): void
    {
        $gameId = $this->setNewGameId();

        $gamePlayers = $this->selectPlayersInGame($request);

        $playersResultCollection = new Collection();
        foreach ($gamePlayers as $player) {
            $playerResult = new OnePlayerResultDTO(
                playerName: $player
            );
            $playersResultCollection->push($playerResult);
        }
        $allPlayersResults = new AllPlayersResultsDTO($playersResultCollection);

        $gameData = new GameDataDTO($gameId, allPlayersResults: $allPlayersResults);

        $request->session()->put('gameData', $gameData);
    }

    /**
     * Get players list from session
     */
    public function getPlayers(): Collection
    {
        $players = $this->getPlayersFromSession();

        return $players;
    }

    /**
     * Calculate points for each player
     */
    public function pointsCalculator(Request $request)
    {
        $gameData = $this->getDataFromSession();
        $selectedPlayers = $this->getPlayersFromSession();

        $bestScore = 0;
        $bestArticaft = 0;
        $bestPlayer = '';

        $this->createGame();

        foreach ($selectedPlayers as $selectedPlayer) {
            $playerId = Player::where('player_name', $selectedPlayer)->value('id');
            $status = $request->input('status_' . $selectedPlayer);

            $statusPoints = ($status == 3) ? 20 : 0;
            $playerBestArtifact = 0;
            $totalPoints = 0;

            if ($status != 1) {

                $gold = ($request->input('gold_' . $selectedPlayer) != null) ? $request->input('gold_' . $selectedPlayer) : 0;
                $tokens = $request->input('tokens_' . $selectedPlayer);
                $cards = $request->input('cards_' . $selectedPlayer);

                $totalArtifactsPoints = 0;

                foreach (ArtifactType::getAllArtifacts() as $artifactPoints) {
                    if ($request->has('art' . $artifactPoints->value . '_' . $selectedPlayer)) {
                        $totalArtifactsPoints += $artifactPoints->value;
                        if ($artifactPoints > $playerBestArtifact) {
                            $playerBestArtifact = $artifactPoints;
                        }
                    }
                }
                // $artifacts = [
                //     'art5_' . $selectedPlayer => 5,
                //     'art7_' . $selectedPlayer => 7,
                //     'art10_' . $selectedPlayer => 10,
                //     'art12_' . $selectedPlayer => 12,
                //     'art15_' . $selectedPlayer => 15,
                //     'art17_' . $selectedPlayer => 17,
                //     'art20_' . $selectedPlayer => 20,
                //     'art25_' . $selectedPlayer => 25,
                //     'art30_' . $selectedPlayer => 30,
                // ];

                // $totalArtifactsPoints = 0;

                // foreach ($artifacts as $artifactName => $artifactPoints) {
                //     if ($request->has($artifactName)) {
                //         $totalArtifactsPoints += $artifactPoints;
                //         if ($artifactPoints > $playerBestArtifact) {
                //             $playerBestArtifact = $artifactPoints;
                //         }
                //     }
                // }

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

    //Create id for new game based on id of the last game
    private function setNewGameId(): int
    {
        $latestGame = Game::latest()->first();
        $lastGameId = $latestGame ? $latestGame->id : 0;
        $newGameId = $lastGameId + 1;

        return $newGameId;
    }


    //Summary of selectPlayersInGame
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

    // Summary of getDataFromSession
    private function getDataFromSession(): GameDataDTO
    {
        $gameData = session()->get('gameData');

        return $gameData;
    }

    // Get the list of players in this game
    private function getPlayersFromSession(): Collection
    {

        $gameData = $this->getDataFromSession();
        $players = $gameData->allPlayersResults->playersResults->map(function ($playerResult) {
            return $playerResult->playerName;
        });

        return $players;
    }


    // Create and add new game to database & create Game as DTO object

    private function createGame(): GamesListDTO
    {
        $game = Game::create([]);
        return new GamesListDTO($game->id, $game->created_at, $game->winner, null,);
    }


    // private function calculateArifatsPoints(): int {}

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
}
