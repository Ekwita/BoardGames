<?php

namespace App\DTOs\NewGameParams;

class OnePlayerResultDTO
{
    public function __construct(
        public ?int $gameId = null,
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
        public ?int $gold = 0,
        public ?int $tokens = 0,
        public ?int $cards = 0,
        public ?int $totalPoints = 0,
    ) {}

    public function toArray(): array
    {
        return [
            'game_id' => $this->gameId,
            'player_id' => $this->playerId,
            'player_name' => $this->playerName,
            'status' => $this->status,
            'art5' => $this->art5,
            'art7' => $this->art7,
            'art10' => $this->art10,
            'art12' => $this->art12,
            'art15' => $this->art15,
            'art17' => $this->art17,
            'art20' => $this->art20,
            'art25' => $this->art25,
            'art30' => $this->art30,
            'gold' => $this->gold,
            'tokens' => $this->tokens,
            'cards' => $this->cards,
            'total_points' => $this->totalPoints
        ];
    }
}
