<?php

namespace App\DTOs;

class OnePlayerResultDTO
{
    public function __construct(
        public ?int $playerId = null,
        public ?string $playerName = null,
        public ?int $status = null,
        public ?bool $art5 = null,
        public ?bool $art7 = null,
        public ?bool $art10 = null,
        public ?bool $art12 = null,
        public ?bool $art15 = null,
        public ?bool $art17 = null,
        public ?bool $art20 = null,
        public ?bool $art25 = null,
        public ?bool $art30 = null,
        public ?int $gold = null,
        public ?int $tokens = null,
        public ?int $cards = null,
        public ?int $totalPoints = null,
    ) {}

}
