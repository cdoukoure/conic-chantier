<?php

namespace Database\Factories;

use App\Models\Contact;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectContact>
 */
class ProjectContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $projectIds = Project::where('type', 'chantier')->pluck('id')->toArray();
        $clientIds = Contact::where('type', 'ouvrier')->pluck('id')->toArray();
        return [
            'project_id' => $this->faker->randomElement($projectIds),
            'contact_id' => $this->faker->randomElement($clientIds),
            'role' => 'ouvrier',
            'hourly_rate' => $this->faker->numberBetween(500, 2000),
        ];
    }
}
