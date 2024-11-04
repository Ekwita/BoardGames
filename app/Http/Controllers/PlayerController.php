<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Interfaces\PlayerServiceInterface;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PlayerController extends Controller
{

    public function __construct(protected PlayerServiceInterface $playerService) {}

    /**
     * Display a listing of the players.
     */
    public function index(): Response
    {
        $players = $this->playerService->getAllPlayers();

        return Inertia::render('Players/List', ['players' => $players]);

    }

    /**
     * Store a newly created player in storage.
     */
    public function store(StorePlayerRequest $request): RedirectResponse
    {
        $this->playerService->createPlayer($request->getDto());

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        $playerDTO = $this->playerService->getPlayerById($id);

        return Inertia::render('Players/Statistic', ['player' => $playerDTO]);
    }
}
