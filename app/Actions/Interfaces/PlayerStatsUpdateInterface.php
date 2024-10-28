<?php

namespace App\Actions\Interfaces;

use App\DTOs\NewGameParams\OnePlayerResultDTO;

interface PlayerStatsUpdateInterface
{
    public function handle(OnePlayerResultDTO $dto): void;
}
