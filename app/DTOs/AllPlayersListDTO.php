<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class AllPlayersListDTO
{
    public function __construct(
        public Collection $players
    ) {}
}
