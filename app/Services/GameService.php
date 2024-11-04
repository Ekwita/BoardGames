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
        $user = Auth::user();
        $players = Player::where('user_id', $user->id)->get();
        
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

        foreach ($playersInGame as $playerId) {
            $playerName = Player::where('id', $playerId)->pluck('player_name')->first();
            $selectedPlayers->push(SelectPlayerFactory::addPlayerToGame($playerId, $playerName));
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
            return [
                'playerId' => $selectedPlayer->playerId,
                'playerName' => $selectedPlayer->playerName
            ];
        });
        // dd($players);
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
