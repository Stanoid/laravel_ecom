<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\recipe>
 */
class RecipeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id'=>fake()->numberBetween(1,100),
            'timeInMinutes'=>fake()->numberBetween(20,180),
            'name'=>fake()->sentence(2),
            'description'=>fake()->paragraph(5),
            'insructions'=>fake()->paragraph(5),
            'serving'=>fake()->numberBetween(1,5),
            'img' => json_encode(array(fake()->imageUrl(),fake()->imageUrl(),fake()->imageUrl())),

        ];
    }
}