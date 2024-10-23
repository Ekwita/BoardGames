<?php

namespace   App\Actions;

use App\DTOs\NewGameParams\GameDataDTO;
use App\Models\Game;
use App\Models\Player;

class SetWinner
{
    public function handle(string $bestPlayer, int $gameId): void
    {
        Game::where('id', $gameId)->update(['winner' => $bestPlayer]);
        Player::where('player_name', $bestPlayer)->increment('wins', 1);
    }
}
