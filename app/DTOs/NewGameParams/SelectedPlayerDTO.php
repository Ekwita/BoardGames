<?php

namespace App\DTOs\NewGameParams;

class SelectedPlayerDTO
{
    public function __construct(
        public int $playerId,
        public string $playerName
    ) {}

    public function toArray(): array
    {
        return [
            'playerId' => $this->playerId,
            'playerName' => $this->playerName
        ];
    }
}
