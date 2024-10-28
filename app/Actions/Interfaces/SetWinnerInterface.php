<?php

namespace App\Actions\Interfaces;

use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\PlayerPointsComparisonDTO;

interface SetWinnerInterface
{
    public function handle(PlayerPointsComparisonDTO $bestPlayer, GameDataDTO $gameData): void;
}