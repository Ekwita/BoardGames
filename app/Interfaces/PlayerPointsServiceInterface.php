<?php

namespace App\Interfaces;

use App\DTOs\NewGameParams\GameDataDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface PlayerPointsServiceInterface
{
    public function calculate(Request $request, Collection $selectedPlayers, GameDataDTO $gameData): ?string;
}
