<?php

namespace Database\Factories;

use App\Models\Surah;
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
            // 'surah_id' => Surah::factory(),
            'number' => $this->faker->numberBetween(1, 200),
            'text' => $this->faker->text(),
            'translation_indo' => $this->faker->text(),
        ];
    }
}
