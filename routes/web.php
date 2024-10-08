<?php

use App\Http\Controllers\GameManagmentController;
use App\Http\Controllers\GamePointsController;
use App\Http\Controllers\GameStatisticsController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\PlayerController;
use Illuminate\Support\Facades\Route;

/**
 * Home page
 */
Route::get('/', [HomePageController::class, 'index'])->name('base');

/**
 * Routes for players resource operation
 */
Route::resource('players', PlayerController::class);

/**
 * Routes for games
 */
Route::prefix('games')->group(function () {
    Route::controller(GameManagmentController::class)->group(function () {
        Route::get('/new-game', 'startNewGame')->name('games.newGame');
        Route::post('/new-game', 'selectPlayers')->name('games.selectPlayers');
        Route::get('/new-game/points', 'pointsForm')->name('games.pointsForm');
    });
    Route::controller(GameStatisticsController::class)->group(function () {
        Route::get('/list', 'gamesList')->name('games.index');
    });
    Route::controller(GamePointsController::class)->group(function () {
        Route::post('/new-game/points', 'pointsCalculate')->name('games.pointsCalculate');
    });
});
