<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class GameDTO
{
    public function __construct(
        public int $gameId,
        public ?string $winner,
        public ?Collection $statistics,
        public string $createdAt,
    ) {}
}
