<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Word>
 */
class WordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'word_group_id' => $this->faker->numberBetween(1, 100),
            'order_number' => $this->faker->numberBetween(1, 10),
            'text' => $this->faker->word(),
            'translation' => $this->faker->word(),
            'kalimat' => $this->faker->randomElement(['Isim', 'Fiil', 'Huruf']),
            'color' => $this->faker->randomElement(['red', 'green', 'blue']),
            'kategori' => $this->faker->word(),
            'hukum' => $this->faker->word(),
            'kedudukan' => $this->faker->word(),
            'irob' => $this->faker->word(),
            'tanda' => $this->faker->word(),
            'simbol' => $this->faker->text(5),
            'editor' => $this->faker->numberBetween(1, 10)

        ];
    }
}
