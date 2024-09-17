<?php

namespace App\Actions\PlayersStats;

use App\Models\Player;

class AlivePlayerStatsUpdate
{
    public function handle(array $data, int $totalPoints): void
    {
        $playerToUpdate = Player::where('player_name', $data['player_name']);
        $playerToUpdate->incrementEach([
            'games' => 1,
            'totalgold' => $data['gold'],
            'art5' => $data['art5'] ? 1 : 0,
            'art7' => $data['art7'] ? 1 : 0,
            'art10' => $data['art10'] ? 1 : 0,
            'art12' => $data['art12'] ? 1 : 0,
            'art15' => $data['art15'] ? 1 : 0,
            'art17' => $data['art17'] ? 1 : 0,
            'art20' => $data['art20'] ? 1 : 0,
            'art25' => $data['art25'] ? 1 : 0,
            'art30' => $data['art30'] ? 1 : 0,
        ]);

        if ($totalPoints > Player::where('player_name', $data['player_name'])->value('best')) {
            Player::where('player_name', $data['player_name'])->update(['best' => $totalPoints]);
        }
    }
}
