<?php

namespace Database\Factories;

use App\Models\Surah;
use App\Models\Verse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WordGroup>
 */
class WordGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'surah_id' => Surah::factory(),
            // 'verse_id' => Verse::factory(),
            'verse_number' => $this->faker->numberBetween(1, 100),
            'order_number' => $this->faker->unique()->randomNumber(5, true),
            'text' => $this->faker->word(),
            'editor' => $this->faker->numberBetween(1, 10)
        ];
    }
}
