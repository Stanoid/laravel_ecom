<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fact>
 */


 class Fact
{
    public $name;
    public $value;
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
}

 class FactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */



    public function definition(): array
    {
        return [

          'facts'=> json_encode( [
                new Fact(fake()->word(), fake()->randomFloat(2, 0, 100)."g"),
                new Fact(fake()->word(), fake()->randomFloat(2, 0, 100)."g"),
                new Fact(fake()->word(), fake()->randomFloat(2, 0, 100)."g"),
                new Fact(fake()->word(), fake()->randomFloat(2, 0, 100)."g"),
                new Fact(fake()->word(), fake()->randomFloat(2, 0, 100)."g"),
                new Fact(fake()->word(), fake()->randomFloat(2, 0, 100)."g"),



            ]),

        ];
    }
}
