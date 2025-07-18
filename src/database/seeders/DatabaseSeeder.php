<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        //*/

        $this->call([
            ContactSeeder::class,
            ProjectSeeder::class,
            FinancialMovementCategorieSeeder::class,
            ProjectContactSeeder::class,
            PhaseSeeder::class
            /* User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]) */
        ]);
    }
}
