<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Verse>
 */
class VerseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // surah_id must available in surah table, so make sure to create surah first before creating verse
            'surah_id' => $this->faker->numberBetween(1, 14),
            'number' => $this->faker->numberBetween(1, 6000),
            'text' => $this->faker->text(),
            'translation_indo' => $this->faker->text(),
        ];
    }
}
