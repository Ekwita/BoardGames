<?php

namespace App\DTOs;

class GameDataDTO
{
    public function __construct(
        public int $id,
        public ?AllPlayersResultsDTO $allPlayersResults = null,
    ) {}
}
