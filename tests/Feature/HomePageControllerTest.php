<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_return_correct_view_with_data(): void
    {
        $this->assertEmpty(session()->all());

        $lastGame = Game::factory()->create();
        $results = Result::factory()->count(3)->create(['game_id' => $lastGame->id]);

        $response = $this->get(route('base'));

        $response->assertStatus(200);
        $response->assertViewIs('welcome');

        $response->assertViewHas('results', $results);
        $response->assertViewHas('game', $lastGame);
    }

    public function test_index_return_correct_view_without_data(): void
    {
        $response = $this->get(route('base'));

        $response->assertStatus(200);
        $response->assertViewIs('welcome');

        $response->assertViewHas('results', null);
        $response->assertViewHas('game', null);
    }
}
