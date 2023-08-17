<?php

namespace Database\Seeders;

use App\Models\Specialist;
use Illuminate\Database\Seeder;

class SpecialistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Specialist::factory()
            ->times(20)
            ->create();
    }
}
