<?php

namespace App\Http\Controllers;

use App\Interfaces\GameInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameManagmentController extends Controller
{

    public function __construct(protected GameInterface $gameService) {}

    /**
     * Show the form for creating a new game.
     */
    public function startNewGame(): View
    {
        $players = $this->gameService->getPlayersList();

        return view('games.select-players', ['players' => $players]);
    }

    /**
     * Creating new game
     * Adding players to game
     * Insert game id & selected players to session.
     */
    public function selectPlayers(Request $request): RedirectResponse
    {
        //Get id of the last game and create ID for new game.
        $this->gameService->createGameWithPlayers($request);

        //Redirect to route counting points
        return redirect()->route('games.pointsForm');
    }

    /**
     * 
     */
    public function pointsForm(): View
    {
        $players = $this->gameService->getPlayersListFromSession();
        return view('games.points', ['players' => $players]);
    }

}
