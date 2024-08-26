<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlayerRequest;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlayerController extends Controller
{
    /**
     * Display a listing of the players.
     */
    public function index(): View
    {
        $players = Player::all();
        return view('players.list', ['players' => $players]);
    }

    /**
     * Store a newly created player in storage.
     */
    public function store(StorePlayerRequest $request)
    {
        $validatedName = $request->input('player_name');
        Player::create([
            'player_name' => $validatedName
        ]);
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        return view('players.statistic', ['player' => $player]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        //
    }
}
