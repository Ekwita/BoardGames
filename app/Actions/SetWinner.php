<?php

namespace   App\Actions;

use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\PlayerPointsComparisonDTO;
use App\Actions\Interfaces\SetWinnerInterface;
use App\Models\Game;
use App\Models\Player;

class SetWinner implements SetWinnerInterface
{
    public function handle(PlayerPointsComparisonDTO $bestPlayer, GameDataDTO $gameData): void
    {
        Game::where('id', $gameData->id)->update(['winner' => $bestPlayer->bestPlayer]);
        Player::where('player_name', $bestPlayer->bestPlayer)->increment('wins', 1);
    }
}
