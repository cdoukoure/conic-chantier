<?php

namespace Database\Seeders;

use App\Models\FinancialMovementCategorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialMovementCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        FinancialMovementCategorie::create([
            'name' => 'Achat de matériel',
            'default_flow_type' => 'out',
        ]);
        FinancialMovementCategorie::create([
            'name' => 'Paiement des ouvriers',
            'default_flow_type' => 'out',
        ]);
        FinancialMovementCategorie::create([
            'name' => 'Paiement des fournisseurs',
            'default_flow_type' => 'out',
        ]);
        FinancialMovementCategorie::create([
            'name' => 'Paiement de salaire',
            'default_flow_type' => 'out',
        ]);

        // FinancialMovementCategorie::factory()->count(5)->create();
    }
}
