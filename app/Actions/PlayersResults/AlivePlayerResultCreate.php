<?php

namespace   App\Actions\PlayersResults;;

use App\Actions\Interfaces\PlayerResultCreateInterface;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Models\Result;

class AlivePlayerResultCreate implements PlayerResultCreateInterface
{
    public function handle(OnePlayerResultDTO $dto): void
    {
        Result::create($dto->toArray());
    }
}
