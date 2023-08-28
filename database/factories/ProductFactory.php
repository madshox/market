<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => [
                'en' => $this->faker->word,
                'ru' => $this->faker->word
            ],
            'category_id' => rand(1, 5),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'image' => $this->faker->imageUrl(),
            'description' => [
                'en' => $this->faker->sentence,
                'ru' => $this->faker->sentence
            ],
            'in_stock' => $this->faker->boolean(),
        ];
    }
}
