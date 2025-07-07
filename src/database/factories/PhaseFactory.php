<?php

namespace Database\Factories;

use App\Models\Phase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Phase>
 */
class PhaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'parent_id' => null, // null pour les phases racine
        ];
    }

    public function sousPhase(Phase $parent): Factory
    {
        return $this->state(function () use ($parent) {
            return [
                'name' => $this->faker->sentence(),
                'description' => $this->faker->paragraph(),
                'parent_id' => $parent->id,
            ];
        });
    }
}
