<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Surah>
 */
class SurahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'name_id' => $this->faker->firstName(),
            'name_en' => $this->faker->firstName(),
            'location' => $this->faker->randomElement(['makkiyah', 'madaniyyah']),
            'verse_count' => $this->faker->numberBetween(3, 200),
        ];
    }
}
