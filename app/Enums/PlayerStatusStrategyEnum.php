<?php

namespace App\Enums;

use App\Interfaces\PlayerStatusStrategyInterface;
use App\Strategies\AlivePlayerPointsStrategy;
use App\Strategies\DeadPlayerPointsStrategy;

enum PlayerStatusStrategyEnum: string
{
    case ALIVE = AlivePlayerPointsStrategy::class;
    case DEAD = DeadPlayerPointsStrategy::class;

    public function make(): PlayerStatusStrategyInterface
    {
        return app($this->value);
    }
}
