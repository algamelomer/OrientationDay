<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            // Generates a random 14-digit number for the national ID
            'national_id' => $this->faker->unique()->numerify('##############'),
            'mobile_phone' => $this->faker->phoneNumber(),
            'place' => null, // The default place is null
        ];
    }
}
