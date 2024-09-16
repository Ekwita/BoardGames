<?php

namespace App\Factories;

use App\DTOs\OnePlayerResultDTO;

class OnePlayerResultFactory
{
    public static function createPlayerResult(int $id, string $playerName): OnePlayerResultDTO
    {
        return new OnePlayerResultDTO(
            playerId: $id,
            playerName: $playerName
        );
    }
}
