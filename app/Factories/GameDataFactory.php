<?php

namespace App\Factories;

use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\SelectedPlayersListDTO;
use Illuminate\Support\Collection;

class GameDataFactory
{
    public static function createGameData(Collection $selectedPlayers): GameDataDTO
    {
        $selectedPlayersList = new SelectedPlayersListDTO($selectedPlayers);
        return new GameDataDTO(null, $selectedPlayersList);
    }
}
