<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Project::factory()->count(10)->create(); // si tu as une factory
        // Créer 5 projets principaux
        Project::factory()->count(10)->create()->each(function ($project) {
            // Pour chaque projet, créer 5 chantiers liés
            /*
            Project::factory()
                ->count(5)
                ->chantier($project)
                ->create();
            //*/
            Project::factory()
                ->chantierWithRelations($project)
                ->count(random_int(2,5))
                ->create();
        });
    }
}
