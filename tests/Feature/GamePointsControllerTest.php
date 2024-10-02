<?php

namespace Tests\Feature;

use App\Actions\CreateGame;
use App\Actions\SetWinner;
use App\DTOs\NewGameParams\AllPlayersResultsDTO;
use App\DTOs\NewGameParams\GameDataDTO;
use App\Interfaces\PointsCalculatorInterface;
use App\Models\Game;
use App\Models\Player;
use App\Models\Result;
use App\Services\GameResultService;
use App\Services\PlayerPointsService;
use App\Services\PointsCalculatorService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GamePointsControllerTest extends TestCase
{

    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    protected PointsCalculatorInterface $pointsCalculatorService;

    public function setUp(): void
    {
        parent::setUp();

        // Wykorzystujemy rzeczywiste implementacje serwisów
        $this->pointsCalculatorService = new PointsCalculatorService(
            new CreateGame(),
            new PlayerPointsService(),
            new GameResultService(),
            app(SetWinner::class)
        );
    }

    public function test_game_points_calculate_points_and_return_view(): void
    {
        $players = Player::factory()->createMany([
            ['player_name' => 'John'],
            ['player_name' => 'Jan']
        ]);

        $playersResults = collect();

        foreach ($players as $player) {
            $playersResults->push((object) [
                'playerId' => $player->id,
                'playerName' => $player->player_name,
            ]);
        }
        // Przygotowanie danych do sesji (symulacja zapisanej gry)
        $gameData = new GameDataDTO(
            1,  // gameId
            new AllPlayersResultsDTO($playersResults),
        );

        session()->put('gameData', $gameData);
        $requestData = [];
        foreach ($players as $player) {
            $requestData = array_merge($requestData, [
                'status_' . $player->player_name => fake()->numberBetween(1, 3),  // Gracz John jest żywy
                'gold_' . $player->player_name => fake()->numberBetween(0, 21),
                'tokens_' . $player->player_name => fake()->numberBetween(0, 40),
                'cards_' . $player->player_name => fake()->numberBetween(0, 50),
                // Dodaj artefakty
                'art5_' . $player->player_name => fake()->boolean(),
                'art7_' . $player->player_name => fake()->boolean(),
                'art10_' . $player->player_name => fake()->boolean(),
                'art12_' . $player->player_name => fake()->boolean(),
            ]);
        }

        $response = $this->post(route('games.pointsCalculate', $requestData));


        $response->assertViewIs('games.result');

        $results = Result::where('game_id', $gameData->id)
            ->orderByDesc('total_points')
            ->orderByDesc('art30')
            ->orderByDesc('art25')
            ->orderByDesc('art20')
            ->orderByDesc('art17')
            ->orderByDesc('art15')
            ->orderByDesc('art12')
            ->orderByDesc('art10')
            ->orderByDesc('art7')
            ->orderByDesc('art5')
            ->get();
        $winner = Game::where('id', $gameData->id)->value('winner');
        $response->assertViewHas('results', $results);
        $response->assertViewHas('winner', $winner);
    }
}
