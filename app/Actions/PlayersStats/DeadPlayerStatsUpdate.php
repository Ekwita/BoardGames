<?php

namespace App\Actions\PlayersStats;

use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Models\Player;

class DeadPlayerStatsUpdate
{
    public function handle(OnePlayerResultDTO $dto): void
    {
        Player::where('player_name', $dto->playerName)->incrementEach([
            'games' => 1,
            'deaths' => 1,
        ]);
    }
}
