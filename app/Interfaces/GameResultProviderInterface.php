<?php

namespace App\Interfaces;

interface GameResultProviderInterface
{
    public function getGameResult(int $gameId): array;
}
