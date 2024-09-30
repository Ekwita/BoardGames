<?php

namespace Tests\Feature;

use App\DTOs\NewGameParams\AllPlayersResultsDTO;
use App\DTOs\NewGameParams\GameDataDTO;
use App\DTOs\NewGameParams\OnePlayerResultDTO;
use App\Models\Game;
use App\Models\Player;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use Tests\TestCase;

class GameManagmentControllerTest extends TestCase
{
    use RefreshDatabase;
    public function test_start_new_game_get_players_list_from_session(): void
    {
        $players = Player::factory()->count(10)->create();

        $response = $this->get(route('games.newGame'));

        $response->assertStatus(200);
        $response->assertViewIs('games.select-players');

        $response->assertViewHas('players', function ($viewPlayers) use ($players) {
            return count($viewPlayers->players) === $players->count() &&
                collect($viewPlayers->players)->pluck('id')->diff($players->pluck('id'))->isEmpty();
        });
    }

    public function test_select_players_creates_game_and_redirects(): void
    {
        $players = Player::factory()->count(6)->create();

        $selectedPlayers = [
            'player1' => $players[0]->player_name,
            'player2' => $players[1]->player_name,
            'player3' => $players[2]->player_name,
            'player4' => $players[3]->player_name,
            'player5' => $players[4]->player_name,
            'player6' => $players[5]->player_name,
        ];

        $response = $this->post(route('games.selectPlayers'), $selectedPlayers);

        $response->assertRedirectToRoute('games.pointsForm');
        $response->assertStatus(302);

        $gameData = session('gameData');

        $this->assertInstanceOf(GameDataDTO::class, $gameData);
        $this->assertNotNull($gameData->allPlayersResults);

        foreach ($gameData->allPlayersResults->playersResults as $playerResult) {
            $this->assertInstanceOf(OnePlayerResultDTO::class, $playerResult);
            $this->assertContains($playerResult->playerName, array_values($selectedPlayers));
        }

        $this->assertDatabaseEmpty('games');

        $this->assertIsInt($gameData->id);
        $this->assertEquals(1, $gameData->id);
    }

    public function test_points_form_return_correct_view(): void
    {
        $players = Player::factory()->count(3)->create();

        $playersResults = collect();

        foreach ($players as $player) {
            $playersResults->push((object) [
                'playerId' => $player->id,
                'playerName' => $player->player_name,
            ]);
        }

        $gameData = new GameDataDTO(
            1,  // gameId
            new AllPlayersResultsDTO($playersResults),
        );

        $this->withSession(['gameData' => $gameData]);

        $response = $this->get(route('games.pointsForm'));

        $response->assertStatus(200);

           $response->assertViewHas('players', function ($playersInView) use ($players) {
            if ($playersInView->count() !== $players->count()) {
                return false;
            }

            foreach ($players as $player) {
                if (!$playersInView->contains($player->player_name)) {
                    return false;
                }
            }

            return true;
        });
    }
}
