<?php

namespace App\Factories;

use App\DTOs\AllPlayersListDTO;
use Illuminate\Support\Collection;

class PlayersListFactory
{
    public static function createAllPlayersList(Collection $players): AllPlayersListDTO
    {
        return new AllPlayersListDTO($players);
    } 
}