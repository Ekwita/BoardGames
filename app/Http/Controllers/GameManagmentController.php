<?php

namespace App\Http\Controllers;

use App\Interfaces\GameInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class GameManagmentController extends Controller
{

    public function __construct(protected GameInterface $gameService) {}

    /**
     * Show the form for creating a new game - players select form.
     */
    public function startNewGame(): Response
    {
        $players = $this->gameService->getPlayersList();
        return Inertia::render('Games/SelectPlayers', [
            'players' => $players
        ]);
    }

    /**
     * Create DTO with selected players.
     * Put DTO to session.
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
    public function displayPointsForm(): Response
    {
        $players = $this->gameService->getPlayersListFromSession();

        return Inertia::render('Games/PointsCalculator', [
            'players' => $players,
        ]);
    }
}
