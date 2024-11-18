<?php

namespace App\Factories;

use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\NewGameParams\SelectedPlayerDTO;
use App\Http\Requests\PlayerPointRequest;

class OnePlayerResultFactory
{
    public function createDto(array $playerData, GameDataDTO $gameData, SelectedPlayerDTO $selectedPlayer): OnePlayerResultDTO
    {
        return new OnePlayerResultDTO(
            gameId: $gameData->id,
            playerId: $selectedPlayer->playerId,
            playerName: $selectedPlayer->playerName,
            status: $playerData['status'] ?? null,
            art5: $playerData['artifacts']['art5'] ?? false,
            art7: $playerData['artifacts']['art7'] ?? false,
            art10: $playerData['artifacts']['art10'] ?? false,
            art12: $playerData['artifacts']['art12'] ?? false,
            art15: $playerData['artifacts']['art15'] ?? false,
            art17: $playerData['artifacts']['art17'] ?? false,
            art20: $playerData['artifacts']['art20'] ?? false,
            art25: $playerData['artifacts']['art25'] ?? false,
            art30: $playerData['artifacts']['art30'] ?? false,
            gold: $playerData['gold'] ?? 0,
            tokens: $playerData['tokens'] ?? 0,
            cards: $playerData['cards'] ?? 0,
        );
    }
}
