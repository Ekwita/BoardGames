<?php

namespace   App\Actions\PlayersResults;

use App\Models\Result;

class DeadPlayerResultCreate
{
    public function handle(array $data): void
    {
        Result::create($data);
    }
}
