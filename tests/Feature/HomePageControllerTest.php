<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class HomePageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_return_correct_view_with_data(): void
    {
        $user = User::factory()->create();
        $players = Player::factory()->count(3)->create();
        $lastGame = Game::factory()->create(['user_id' => $user->id, 'winner' => $players[0]->player_name]);

        foreach ($players as $player) {
            Result::factory()->create(['game_id' => $lastGame->id, 'player_id' => $player->id, 'player_name' => $player->player_name]);
        }


        $response = $this->actingAs($user)->get(route('base'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Welcome')
                ->has('results')
                ->has('game')
        );
    }

    public function test_index_return_correct_view_without_data(): void
    {
        $response = $this->get(route('base'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Welcome')
                ->has('results')
                ->where('results', [])
                ->has('game', null)
        );
    }
}
