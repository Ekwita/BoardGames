<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface GameInterface
{
    public function createGameWithPlayers(Request $request);
    public function getPlayersListFromSession();
}
