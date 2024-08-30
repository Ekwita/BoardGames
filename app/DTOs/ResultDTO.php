<?php

namespace App\DTOs;

class ResultDTO
{
    public function __construct(
        public int $gameId,
        public int $playerId,
        public string $playerName,
        public int $status,
        public int $gold,
        public int $tokens,
        public int $cards,
        public int $totalPoints,
        public array $artifacts
    ) {}
}
