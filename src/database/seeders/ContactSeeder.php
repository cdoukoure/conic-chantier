<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        Contact::create([
            'type' => 'client',
            'name' => 'Jean Dupont',
            'email' => 'jean.dupont@example.com',
            'phone' => '0123456789',
            'address' => '10 rue de Paris',
            'siret' => '12345678901234',
            'metadata' => json_encode(['notes' => 'Client fidèle']),
        ]);
        //*/

        Contact::factory()->count(20)->create(); // si tu as une factory
    }
}
