<?php

namespace App\Factories;

use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\DTOs\NewGameParams\SelectedPlayerDTO;
use App\Http\Requests\PlayerPointRequest;

class OnePlayerResultFactory
{
    public function createDto(GameDataDTO $gameData, SelectedPlayerDTO $selectedPlayer, PlayerPointRequest $request): OnePlayerResultDTO
    {
        return new OnePlayerResultDTO(
            gameId: $gameData->id,
            playerId: $selectedPlayer->playerId,
            playerName: $selectedPlayer->playerName,
            status: $request->input('status_' . $selectedPlayer->playerId),
            art5: $request->has('art5_' . $selectedPlayer->playerId),
            art7: $request->has('art7_' . $selectedPlayer->playerId),
            art10: $request->has('art10_' . $selectedPlayer->playerId),
            art12: $request->has('art12_' . $selectedPlayer->playerId),
            art15: $request->has('art15_' . $selectedPlayer->playerId),
            art17: $request->has('art17_' . $selectedPlayer->playerId),
            art20: $request->has('art20_' . $selectedPlayer->playerId),
            art25: $request->has('art25_' . $selectedPlayer->playerId),
            art30: $request->has('art30_' . $selectedPlayer->playerId),
            gold: $request->input('gold_' . $selectedPlayer->playerId) ?? 0,
            tokens: $request->input('tokens_' . $selectedPlayer->playerId) ?? 0,
            cards: $request->input('cards_' . $selectedPlayer->playerId) ?? 0,
        );
    }
}
