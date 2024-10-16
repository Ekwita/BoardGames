<?php

namespace App\Services;

use App\DTOs\AllPlayersListDTO;
use App\DTOs\NewGameParams\GameDataDTO;
use App\Factories\GameDataFactory;
use App\Factories\OnePlayerResultFactory;
use App\Factories\PlayersListFactory;
use App\Interfaces\GameInterface;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GameService implements GameInterface
{

    public function getPlayersList(): AllPlayersListDTO
    {
        $players = Auth::user()->players()->select('id', 'player_name')->get();
        $allPlayersList =  PlayersListFactory::createAllPlayersList($players);

        return $allPlayersList;
    }

    /**
     * Create new GameDataDTO object
     * Put data to session
     */
    public function createGameWithPlayers(Request $request): void
    {
        // $gameId = $this->createNewGameId();

        $playersInGame = $this->selectPlayersInGame($request);
        $playersResultCollection = new Collection();

        foreach ($playersInGame as $player) {

            $playerId = Player::where('player_name', $player)->pluck('id')->first();
            $playersResultCollection->push(OnePlayerResultFactory::createPlayerResult($playerId, $player));
        }

        $gameData = GameDataFactory::createGameData($playersResultCollection);

        $request->session()->put('gameData', $gameData);
    }

    /**
     * Get players list from session
     */
    public function getPlayersListFromSession(): Collection
    {
        $gameData = $this->getDataFromSession();
        $players = $gameData->allPlayersResults->playersResults->map(function ($playerResult) {
            return $playerResult->playerName;
        });

        return $players;
    }


    //PRIVATE FUNCTIONS

    //Create id for new game based on id of the last game
    // private function createNewGameId(): int
    // {
    //     $latestGame = Game::latest()->first();
    //     $lastGameId = $latestGame ? $latestGame->id : 0;
    //     $newGameId = $lastGameId + 1;

    //     return $newGameId;
    // }


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
}
