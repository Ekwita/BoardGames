<?php

namespace App\Factories;

use App\DTOs\NewGameParams\AllPlayersResultsDTO;
use App\DTOs\NewGameParams\GameDataDTO;
use Illuminate\Support\Collection;

class GameDataFactory
{
    public static function createGameData(Collection $playersResults): GameDataDTO
    {
        $allPlayersResults = new AllPlayersResultsDTO($playersResults);
        return new GameDataDTO(null, $allPlayersResults);
    }
}
