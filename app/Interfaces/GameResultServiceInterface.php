<?php

namespace App\Interfaces;

interface GameResultServiceInterface
{
    public function getGameResult(int $gameId): array;
}
