<?php

namespace App\Http\Controllers;

use App\DTOs\PlayerDTO;
use App\Http\Requests\StorePlayerRequest;
use App\Services\PlayerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Inertia\Inertia;
use Inertia\Response;

class PlayerController extends Controller
{

    public function __construct(protected PlayerService $playerService) {}

    /**
     * Display a listing of the players.
     */
    public function index(): Response
    {
        $players = $this->playerService->getAllPlayers();

        return Inertia::render('Players/List', ['players' => $players]);

        // return view('players.list', ['players' => $players]);
    }

    /**
     * Store a newly created player in storage.
     */
    public function store(StorePlayerRequest $request): RedirectResponse
    {
        // dd($user);
        $playerDTO = new PlayerDTO(
            id: 0,
            user_id: $request->user()->id,
            player_name: $request->input('player_name')
        );
        $this->playerService->createPlayer($playerDTO);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): Response
    {
        $playerDTO = $this->playerService->getPlayerById($id);

        return Inertia::render('Players/Statistic', ['player' => $playerDTO]);
    }
}
