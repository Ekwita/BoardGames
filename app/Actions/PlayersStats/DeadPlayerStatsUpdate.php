<?php

namespace App\Actions\PlayersStats;

use App\Models\Player;

class DeadPlayerStatsUpdate
{
    public function handle(array $data): void
    {
        Player::where('player_name', $data['player_name'])->incrementEach([
            'games' => 1,
            'deaths' => 1,
        ]);
    }
}
