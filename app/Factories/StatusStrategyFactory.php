<?php

namespace App\Factories;

use App\Enums\PlayerStatusStrategyEnum;
use App\Factories\Interfaces\StatusStrategyInterface;
use App\Interfaces\PlayerStatusStrategyInterface;

class StatusStrategyFactory implements StatusStrategyInterface
{
    public function chooseStrategy(int $playerStatus): ?PlayerStatusStrategyInterface
    {
        $playerPointsStrategy = null;

        foreach (PlayerStatusStrategyEnum::cases() as $strategy) {
            $playerPointsStrategy = $strategy->make();
            if ($playerPointsStrategy->isSatisfiedBy($playerStatus)) {
                return $playerPointsStrategy;
            }
        };

        return null;
    }
}