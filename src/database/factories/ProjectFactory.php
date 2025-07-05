<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clientIds = Contact::where('type', 'client')->pluck('id')->toArray();
        // $start_date = $this->faker->date();
        return [
            'type' => 'projet',
            'name' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'budget' => $this->faker->numberBetween(500000, 100000000),
            // 'start_date' => $start_date,
            // 'end_date' => $start_date->,
            'client_id' => $this->faker->randomElement($clientIds),
            # 'client_id' => Contact::factory()->create(['type' => 'client'])->id,
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'end_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'parent_id' => null, // null pour les projets racine
            'custom_fields' => json_encode([
                'note' => $this->faker->sentence(),
                'source' => $this->faker->randomElement(['import', 'manuel', 'api']),
            ]),
        ];
    }
}
