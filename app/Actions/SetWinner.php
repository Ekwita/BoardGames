<?php

namespace   App\Actions;

use App\Models\Game;
use App\Models\Player;

class SetWinner
{
    public function handle($gameData, string $bestPlayer): void
    {
        Game::where('id', $gameData->id)->update(['winner' => $bestPlayer]);
        Player::where('player_name', $bestPlayer)->increment('wins', 1);
    }
}
