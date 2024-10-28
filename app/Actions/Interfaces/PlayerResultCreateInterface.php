<?php

namespace App\Actions\Interfaces;

use App\DTOs\NewGameParams\OnePlayerResultDTO;

interface PlayerResultCreateInterface
{
    public function handle(OnePlayerResultDTO $dto): void;
}
