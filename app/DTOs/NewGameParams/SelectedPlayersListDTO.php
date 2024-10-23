<?php

namespace App\DTOs\NewGameParams;

use Illuminate\Support\Collection;

class SelectedPlayersListDTO
{
    public function __construct(public Collection $selectedPlayers) {}
}
