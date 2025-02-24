<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Test;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Test::truncate();
        Test::factory()->count(100)->create();
    }
}
