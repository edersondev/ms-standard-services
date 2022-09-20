<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->country(),
            'iso_code' => fake()->countryCode(),
            'iso_code3' => fake()->countryISOAlpha3(),
            'number_code' => fake()->numberBetween(10,999),
            'dial' => fake()->numberBetween(10,200)
        ];
    }
}
