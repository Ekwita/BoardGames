<?php

namespace App\DTOs\NewGameParams;

class PlayerPointsComparisonDTO
{
    public function __construct(
        public ?int $bestScore = 0,
        public ?int $bestArtifact = 0,
        public ?string $bestPlayer = ''
    ) {}

    public function toArray(): array
    {
        return [
            'bestScore' => $this->bestScore,
            'bestArtifact' => $this->bestArtifact,
            'bestPlayer' => $this->bestPlayer
        ];
    }
}
