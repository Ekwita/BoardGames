<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Result>
 */
class ResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $player = Player::factory()->create();
        return [
            'game_id' => Game::factory(),
            'player_id' => $player->id,
            'player_name' => $player->player_name,
            'status' => fake()->numberBetween(1, 3),
            'gold' => fake()->numberBetween(0, 100),
            'tokens' => fake()->numberBetween(0, 100),
            'cards' => fake()->numberBetween(0, 100),
            'art5' => fake()->boolean(),
            'art7' => fake()->boolean(),
            'art10' => fake()->boolean(),
            'art12' => fake()->boolean(),
            'art15' => fake()->boolean(),
            'art17' => fake()->boolean(),
            'art20' => fake()->boolean(),
            'art25' => fake()->boolean(),
            'art30' => fake()->boolean(),
            'total_points' => fake()->numberBetween(0, 300)
        ];
    }
}
