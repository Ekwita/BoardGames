<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GameStatisticControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_games_list_return_correct_view_without_data(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/List')
                ->has('games')
                ->where('games', null)
        );
    }

    public function test_games_list_return_correct_view_with_data(): void
    {
        $user = User::factory()->create();

        $games = Game::factory()->count(5)->create(['user_id' => $user->id]);
        $game = $games->last();

        foreach ($games as $game) {
            Result::factory()->count(3)->create(['game_id' => $game->id]);
        }

        $response = $this->actingAs($user)->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/List')
                ->has('games')
                ->has('games.data', 1)
                ->has(
                    'games.data.0',
                    fn(Assert $page) => $page
                        ->where('gameId', $game->id)
                        ->where('winner', $game->winner)
                        ->where('statistics', $game->results)
                        ->where('createdAt', $game->created_at->format('d-m-Y H:i:s'))
                )
        );
    }
}
