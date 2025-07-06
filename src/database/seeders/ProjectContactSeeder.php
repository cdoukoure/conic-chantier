<?php

namespace Database\Seeders;

use App\Models\ProjectContact;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ProjectContact::factory()->count(40)->create();
    }
}
