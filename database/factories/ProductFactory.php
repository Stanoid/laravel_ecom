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
    public function definition(): array
    {
        return [
            //'user_id'=>1,
            'fact_id'=>fake()->numberBetween(1,40),
            'category_id'=>fake()->numberBetween(1,9),
            'brand_id'=>fake()->numberBetween(1,3),
            'name'=>fake()->sentence(1),
            'origin_country'=>fake()->country(),
            'discount'=>fake()->numberBetween(1,100),
            'size'=>fake()->numberBetween(10,100)."g",
            'expiration_date'=>fake()->date(),
            'description'=>fake()->paragraph(1),
            'img' => json_encode(fake()->imageUrl()),
            'stock' => fake()->numberBetween(10,100),
            'price' => fake()->numberBetween(200, 1000),


        ];
    }
}
