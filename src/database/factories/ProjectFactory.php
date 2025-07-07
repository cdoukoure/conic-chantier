<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\FinancialMovement;
use App\Models\FinancialMovementCategorie;
use App\Models\Project;
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
            // 'budget' => $this->faker->numberBetween(500000, 100000000),
            // 'start_date' => $start_date,
            // 'end_date' => $start_date->,
            'client_id' => $this->faker->randomElement($clientIds),
            # 'client_id' => Contact::factory()->create(['type' => 'client'])->id,
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            // 'end_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'parent_id' => null, // null pour les projets racine
            'custom_fields' => json_encode([
                'note' => $this->faker->sentence(),
                'source' => $this->faker->randomElement(['import', 'manuel', 'api']),
            ]),
        ];
    }

    public function chantier(Project $parent): Factory
    {
        return $this->state(function () use ($parent) {
            return [
                'type' => 'chantier',
                'description' => $this->faker->paragraph(),
                'parent_id' => $parent->id,
                'client_id' => $parent->client_id, // hérite du client du parent
                'budget' => $this->faker->numberBetween(500000, 100000000),
                'start_date' => $this->faker->dateTimeBetween($parent->start_date, '+15 day'),
                'end_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            ];
        });
    }
    
    /**
     * Crée un chantier (sous-projet) avec contacts et mouvements financiers
     */
    public function chantierWithRelations(Project $parent)
    {
        return $this->state(function (array $attributes) use ($parent) {
            return [
                'type' => 'chantier',
                'description' => $this->faker->paragraph(),
                'parent_id' => $parent->id,
                'client_id' => $parent->client_id, // hérite du client du parent
                'budget' => $this->faker->numberBetween(500000, 100000000),
                'start_date' => $this->faker->dateTimeBetween($parent->start_date, '+15 day'),
                'end_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            ];
        })->afterCreating(function (Project $chantier) {
            // Sélectionne jusqu’à 5 contacts existants (hors clients)
            $contacts = Contact::where('type', 'ouvrier')
                ->inRandomOrder()
                ->take(rand(5, 12))
                ->get();

            foreach ($contacts as $contact) {
                $chantier->contacts()->attach($contact->id, [
                    'role' => $contact->type,
                    'hourly_rate' => rand(1000, 5000),
                ]);
            }

            // Crée 3 à 7 mouvements financiers pour ce chantier
            $catIds = FinancialMovementCategorie::all()->pluck('id')->toArray();
            FinancialMovement::factory()
                ->count(rand(3, 7))
                ->create([
                    'project_id' => $chantier->id,
                    'category_id' => $this->faker->randomElement($catIds),
                    // Optionnel : contact_id aléatoire parmi les attachés
                    // 'contact_id' => $contacts->random()->id ?? null,
                    'flow_type' => 'out',
                    'amount' => random_int(5000, 50000000),
                    'operation_date' => $this->faker->dateTime(),
                    'reference' => $this->faker->word(),
                    'payment_method' => $this->faker->randomElement(['cash', 'check', 'transfer', 'card']),
                    'description' => $this->faker->paragraph(),
                ]);
        });
    }
}
