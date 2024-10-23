<?php

namespace   App\Actions\PlayersResults;

use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Models\Result;

class DeadPlayerResultCreate
{
    public function handle(OnePlayerResultDTO $dto): void
    {
        Result::create($dto->toArray());
    }
}
