<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['client', 'fournisseur', 'prestataire', 'ouvrier', 'autre']),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'siret' => $this->faker->numerify('##############'), // 14 chiffres
            'metadata' => json_encode([
                'note' => $this->faker->sentence(),
                'source' => $this->faker->randomElement(['import', 'manuel', 'api']),
            ]),
        ];
    }
}
