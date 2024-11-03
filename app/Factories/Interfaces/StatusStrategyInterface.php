<?php

namespace App\Factories\Interfaces;

use App\Interfaces\PlayerStatusStrategyInterface;

interface StatusStrategyInterface
{
    public function chooseStrategy(int $playerStatus): ?PlayerStatusStrategyInterface;
}
