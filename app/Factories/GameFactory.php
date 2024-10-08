<?php

namespace App\Factories;

use App\DTOs\NewGameParams\AllPlayersResultsDTO;
use App\DTOs\NewGameParams\GameDataDTO;
use Illuminate\Support\Collection;

class GameFactory
{
    public static function createGameData(int $gameId, Collection $playersResults): GameDataDTO
    {
        $allPlayersResults = new AllPlayersResultsDTO($playersResults);
        return new GameDataDTO($gameId, $allPlayersResults);
    }
}
