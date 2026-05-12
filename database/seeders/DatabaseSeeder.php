<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // استدعاء الـ UserSeeder لتنفيذ عملية الإضافة
        $this->call([
            UserSeeder::class,
        ]);
    }
}