<?php

namespace   App\Actions\PlayersResults;;

use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Models\Result;

class AlivePlayerResultCreate
{
    public function handle(OnePlayerResultDTO $dto): void
    {
        Result::create($dto->toArray());
    }
}
