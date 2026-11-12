<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AccessSeeder::class,
            ContentSeeder::class,
            ProgramSeeder::class,
            QuizSeeder::class,
            RewardSeeder::class,
            DemoActivitySeeder::class,
        ]);
    }
}
