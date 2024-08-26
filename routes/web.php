<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('base');

/**
 * Routes for players resource operation
 */
Route::resource('players', PlayerController::class);

/**
 * Routes for games
 */
Route::prefix('games')->group(function () {
    Route::controller(GameController::class)->group(function () {
        Route::get('/list', 'gamesList')->name('games.index'); // Working
        Route::get('/new-game', 'newGame')->name('games.newGame'); //Working
        Route::post('/new-game', 'selectPlayers')->name('games.selectPlayers'); //Working
        Route::get('/new-game/points', 'pointsForm')->name('games.pointsForm');
        Route::post('/new-game/points', 'pointsCalculate')->name('games.pointsCalculate');
    });
});
