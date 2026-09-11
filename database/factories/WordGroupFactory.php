<?php

namespace Database\Factories;

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
            'surah_id' => $this->faker->numberBetween(1, 14),
            'verse_number' => $this->faker->numberBetween(1, 100),
            'verse_id' => $this->faker->numberBetween(1, 100),
            'order_number' => $this->faker->numberBetween(1, 10),
            'text' => $this->faker->word(),
            'editor' => $this->faker->numberBetween(1, 10)
        ];
    }
}
