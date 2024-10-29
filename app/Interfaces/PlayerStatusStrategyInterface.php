<?php

namespace App\Interfaces;

interface PlayerStatusStrategyInterface
{
    public function isSatisfiedBy(int $playerStatus): bool;
}
