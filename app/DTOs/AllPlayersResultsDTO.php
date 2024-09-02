<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class AllPlayersResultsDTO
{

    public function __construct(
        public ?Collection $playersResults = null,
    ) {}
}
