<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['analysis', 'multiple_choice']),
            'level_id' => $this->faker->numberBetween(1, 5),
            'display_order' => $this->faker->numberBetween(1, 10),
            'is_active' => $this->faker->boolean(),
            'attempts' => $this->faker->numberBetween(0, 10),
            'passed' => $this->faker->numberBetween(0, 10),
            'created_by' => $this->faker->numberBetween(1, 5),
        ];
    }
}
