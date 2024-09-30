<?php

namespace Tests\Feature;

use App\DTOs\GamesListDTO;
use App\Models\Game;
use App\Models\Result;
use App\Services\StatisticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Mockery\Mock;
use Tests\TestCase;

class GameStatisticsControllerTest extends TestCase
{

    use RefreshDatabase;

    public function test_games_list_return_correct_view_with_data(): void
    {
        $games = Game::factory()->count(5)->create();
        foreach ($games as $game) {
            Result::factory()->count(3)->create(['game_id' => $game->id]);
        }

        $gamesListMockData = $games->map(function ($game) {
            return new GamesListDTO(
                gameId: $game->id,
                winner: $game->winner,
                statistics: Result::where('game_id', $game->id)->get(),
                createdAt: $game->created_at->format('d-m-Y H:i:s')
            );
        });

        $paginatedGamesMock = new \Illuminate\Pagination\LengthAwarePaginator(
            $gamesListMockData,
            5,
            1,
            1,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $statisticsServiceMock = Mockery::mock(StatisticsService::class);


        $statisticsServiceMock->shouldReceive('getGamesList')
            ->once()
            ->andReturn(['games' => $paginatedGamesMock]);

        $this->instance(StatisticsService::class, $statisticsServiceMock);

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertViewIs('games.list');

        $response->assertViewHas('games', function ($viewGames) use ($paginatedGamesMock) {
            return $viewGames->total() === $paginatedGamesMock->total() &&
                $viewGames->count() === $paginatedGamesMock->count();
        });
    }

    public function test_games_list_return_correct_view_without_data(): void
    {
        $statisticsServiceMock = Mockery::mock(StatisticsService::class);


        $statisticsServiceMock->shouldReceive('getGamesList')
            ->once()
            ->andReturn(['games' => null]);

        $this->instance(StatisticsService::class, $statisticsServiceMock);

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertViewIs('games.list');

        $response->assertViewHas('games', null);
    }

    public function test_games_list_return_correct_view_with_data_without_mocking(): void
    {
        $games = Game::factory()->count(5)->create();
        foreach ($games as $game) {
            Result::factory()->count(3)->create(['game_id' => $game->id]);
        }

        $response = $this->get(route('games.index'));

        $response->assertStatus(200);
        $response->assertViewIs('games.list');


        $response->assertViewHas('games', function ($viewGames) use ($games) {

            return $viewGames->total() === $games->count();
        });
    }
}
