<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'shelter_id' => User::factory()->create(['role' => 'shelter']),
            'name' => $this->faker->firstName(),
            'species' => $this->faker->randomElement(['dog', 'cat']),
            'breed' => $this->faker->word(),
            'age' => $this->faker->numberBetween(1, 12),
            'size' => $this->faker->randomElement(['small', 'medium', 'large']),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'description' => $this->faker->paragraph(),
            'status' => 'available',
            'photo_url' => null,
            'vaccinated' => $this->faker->boolean(70),
            'is_sterilized' => $this->faker->boolean(60),
        ];
    }
}
