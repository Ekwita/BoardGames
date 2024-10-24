<?php

namespace App\DTOs\Players;

class CreatePlayerDTO
{
    public function __construct(
        public string $userId,
        public string $playerName,
    ){}

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'player_name' => $this->playerName,
        ];
    }
}