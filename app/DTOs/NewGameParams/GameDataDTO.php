<?php

namespace App\DTOs\NewGameParams;

class GameDataDTO
{
    public function __construct(
        public ?int $id,
        public ?AllPlayersResultsDTO $allPlayersResults = null,
    ) {}
}
