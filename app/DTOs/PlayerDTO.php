<?php

namespace App\DTOs;

class PlayerDTO
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string $player_name,
        public int $games = 0,
        public int $wins = 0,
        public int $deaths = 0,
        public int $best = 0,
        public int $average = 0,
        public ?int $totalGold = 0,
        public int $art5 = 0,
        public int $art7 = 0,
        public int $art10 = 0,
        public int $art12 = 0,
        public int $art15 = 0,
        public int $art17 = 0,
        public int $art20 = 0,
        public int $art25 = 0,
        public int $art30 = 0
    ) {}
}
