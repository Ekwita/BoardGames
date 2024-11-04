<?php

namespace Tests\Feature;

use App\DTOs\NewGameParams\SelectedPlayerDTO;
use App\DTOs\NewGameParams\SelectedPlayersListDTO;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GamePointControllerTest extends TestCase
{

    use RefreshDatabase;
    public function test_game_points_calculate_points_and_return_view(): void
    {
        $user = User::factory()->create();

        $players = Player::factory()->count(6)->create();
        $playersList = collect();
        foreach ($players as $player) {
            $onePlayer = new SelectedPlayerDTO($player->id, $player->player_name);
            $playersList->push($onePlayer);
        }
        $playersList = new SelectedPlayersListDTO($playersList);

        $requestData = [];
        foreach ($players as $player) {
            $requestData = array_merge($requestData, [
                'status_' . $player->id => fake()->numberBetween(1, 3),
                'gold_' . $player->id => fake()->numberBetween(0, 21),
                'tokens_' . $player->id => fake()->numberBetween(0, 40),
                'cards_' . $player->id => fake()->numberBetween(0, 50),
                // Artifacts
                'art5_' . $player->id => fake()->boolean(),
                'art7_' . $player->id => fake()->boolean(),
                'art10_' . $player->id => fake()->boolean(),
                'art12_' . $player->id => fake()->boolean(),
            ]);
        }

        $this->withSession(['selectedPlayers' => $playersList]);

        $response = $this->actingAs($user)->post(route('games.pointsCalculate', $requestData));


        // $response->assertStatus(200);
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/Result')
        );
    }
}
