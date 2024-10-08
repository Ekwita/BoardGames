<?php

namespace App\DTOs;

use App\DTOs\NewGameParams\GameDataDTO;
use Illuminate\Http\Request;

class PlayerGameDataDTO
{
    public function __construct(
        public Request $request,
        public string $selectedPlayer,
        public int $status,
        public GameDataDTO $gameData,
        public int $playerId,
        public int $playerBestArtifact
    ) {}
}
