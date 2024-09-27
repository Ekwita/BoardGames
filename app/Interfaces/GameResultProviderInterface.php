<?php

namespace App\Interfaces;

use App\DTOs\NewGameParams\GameDataDTO;

interface GameResultProviderInterface
{
    public function getGameResult(GameDataDTO $gameData): array;
}
