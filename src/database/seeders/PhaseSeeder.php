<?php

namespace Database\Seeders;

use App\Models\Phase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer 5 phases principaux
        Phase::factory()->count(10)->create()->each(function ($phase) {
            // Pour chaque phase, créer 5 sous-phases liées
            Phase::factory()
                ->count(5)
                ->sousPhase($phase)
                ->create();
        });
    }
}
