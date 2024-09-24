<?php

namespace App\Interfaces;

use App\DTOs\GameDataDTO;
use App\DTOs\PlayerGameDataDTO;
use Illuminate\Http\Request;

interface PlayerPointsCalculatorInterface
{
    public function calculatePoints(PlayerGameDataDTO $playerGameDataDTO): array;
}
