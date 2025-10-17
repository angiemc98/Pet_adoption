<?php

namespace Database\Factories;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AdoptionApplication>
 */
class AdoptionApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $shelter = User::factory()->state(['role' => 'shelter'])->create();
        $pet = Pet::factory()->create(['shelter_id' => $shelter->id]);
        $adopter = User::factory()->state(['role' => 'adopter'])->create();
        return [
            'pet_id' => $pet->id,
            'adopter_id' => $adopter->id,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason_for_adoption' => $this->faker->sentence(8),
            'has_experience' => $this->faker->boolean(60),
        ];
    }
}
