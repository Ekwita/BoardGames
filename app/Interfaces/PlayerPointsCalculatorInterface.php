<?php

namespace App\Interfaces;

use App\DTOs\NewGameParams\OnePlayerResultDTO;


interface PlayerPointsCalculatorInterface
{
    public function calculatePoints(OnePlayerResultDTO $dto): array;
}
