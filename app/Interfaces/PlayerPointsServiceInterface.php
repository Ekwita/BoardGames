<?php

namespace App\Interfaces;

use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\NewGameParams\PlayerPointsComparisonDTO;

interface PlayerPointsServiceInterface
{
    public function calculate(OnePlayerResultDTO $dto, PlayerPointsComparisonDTO $playerPoints): PlayerPointsComparisonDTO;
}
