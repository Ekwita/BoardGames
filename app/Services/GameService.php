<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\AllPlayersListDTO;
use App\DTOs\NewGameParams\SelectedPlayersListDTO;
use App\Factories\PlayersListFactory;
use App\Factories\SelectPlayerFactory;
use App\Interfaces\GameInterface;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class GameService implements GameInterface
{

    //Get players list from database
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

        $playersInGame = $this->selectPlayersInGame($request);
        $selectedPlayers = collect();

        foreach ($playersInGame as $player) {
            $playerId = Player::where('player_name', $player)->pluck('id')->first();
            $selectedPlayers->push(SelectPlayerFactory::addPlayerToGame($playerId, $player));
        }

        $playersList = new SelectedPlayersListDTO($selectedPlayers);

        $request->session()->put('selectedPlayers', $playersList);
    }

    /**
     * Get players list from session
     */
    public function getPlayersListFromSession(): Collection
    {
        $playersList = session()->get('selectedPlayers');
        $players = $playersList->selectedPlayers->map(function ($selectedPlayer) {
            return $selectedPlayer->playerName;
        });

        return $players;
    }


    //PRIVATE FUNCTIONS

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
}
