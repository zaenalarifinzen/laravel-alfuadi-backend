<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExerciseLevel>
 */
class ExerciseLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->word(),
            'level_number' => $this->faker->numberBetween(1, 10),
            'display_order' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(),
            'metadata' => null,
        ];
    }
}
