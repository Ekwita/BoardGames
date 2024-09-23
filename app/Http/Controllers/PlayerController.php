<?php

namespace App\Http\Controllers;

use App\DTOs\PlayerDTO;
use App\Http\Requests\StorePlayerRequest;
use App\Services\PlayerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PlayerController extends Controller
{

    public function __construct(protected PlayerService $playerService) {}

    /**
     * Display a listing of the players.
     */
    public function index(): View
    {
        $players = $this->playerService->getAllPlayers();
        return view('players.list', ['players' => $players]);
    }

    /**
     * Store a newly created player in storage.
     */
    public function store(StorePlayerRequest $request): RedirectResponse
    {
        $playerDTO = new PlayerDTO(
            id: 0,
            player_name: $request->input('player_name')
        );
        $this->playerService->createPlayer($playerDTO);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $playerDTO = $this->playerService->getPlayerById($id);
        return view('players.statistic', ['player' => $playerDTO]);
    }
}
