<?php

namespace Tests\Feature;


use App\DTOs\NewGameParams\SelectedPlayersListDTO;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class GameManagmentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_new_game_get_players_list(): void
    {
        $user = User::factory()->create();

        $players = Player::factory()->count(6)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('games.newGame'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/SelectPlayers')
                ->has('players.players', 6)
                ->where('players.players.0.player_name', $players[0]->player_name)
        );
    }

    public function test_select_players_creates_game_and_redirects(): void
    {
        $user = User::factory()->create();
        $players = Player::factory()->count(6)->create();

        $selectedPlayers = [
            'player1' => $players[0]->id,
            'player2' => $players[1]->id,
            'player3' => $players[2]->id,
            'player4' => $players[3]->id,
            'player5' => $players[4]->id,
            'player6' => $players[5]->id,
        ];

        $response = $this->actingAs($user)->post(route('games.selectPlayers'), $selectedPlayers);

        $response->assertRedirectToRoute('games.pointsForm');
        // $response->assertSessionHas('selectedPlayers');

        $response->assertSessionHas('selectedPlayers', function ($selectedPlayersListDTO) use ($players) {

            if (! $selectedPlayersListDTO instanceof SelectedPlayersListDTO) {
                return false;
            }

            $selectedPlayersCollection = $selectedPlayersListDTO->selectedPlayers;

            if ($selectedPlayersCollection->count() !== 6) {
                return false;
            }
            return $selectedPlayersCollection->pluck('playerName')->toArray() === $players->pluck('player_name')->toArray();
        });
        $response->assertStatus(302);
    }

    public function test_points_form_return_correct_view(): void
    {
        $user = User::factory()->create();

        $players = Player::factory()->count(3)->create();

        $playersResults = new SelectedPlayersListDTO($players);

        $this->withSession(['selectedPlayers' => $playersResults]);

        $response = $this->actingAs($user)->get(route('games.pointsForm'));

        $response->assertStatus(200);
        $response->assertInertia(
            fn(Assert $page) => $page
                ->component('Games/PointsCalculator')
                ->has('players', 3)
        );
    }
}
