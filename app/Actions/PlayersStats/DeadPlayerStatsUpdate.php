<?php

namespace App\Actions\PlayersStats;

use App\Actions\Interfaces\PlayerStatsUpdateInterface;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Models\Player;

class DeadPlayerStatsUpdate implements PlayerStatsUpdateInterface
{
    public function handle(OnePlayerResultDTO $dto): void
    {
        Player::where('id', $dto->playerId)->incrementEach([
            'games' => 1,
            'deaths' => 1,
        ]);
    }
}
