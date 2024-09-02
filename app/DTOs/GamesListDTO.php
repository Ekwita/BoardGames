<?php

namespace App\DTOs;

use Illuminate\Support\Collection;

class GamesListDTO
{
    public function __construct(
        public int $gameId,
        public ?string $createdAt = null,
        public ?string $winner = null,
        public ?Collection $statistics = null,

    ) {}
}
