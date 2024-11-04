<?php

namespace App\Factories;

use App\DTOs\NewGameParams\SelectedPlayerDTO;

class SelectPlayerFactory
{
    public static function addPlayerToGame(string $id, string $playerName): SelectedPlayerDTO
    {
        return new SelectedPlayerDTO(
            playerId: $id,
            playerName: $playerName
        );
    }
}
