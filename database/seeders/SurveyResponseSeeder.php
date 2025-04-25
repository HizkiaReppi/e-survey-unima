<?php

namespace Database\Seeders;

use App\Models\SurveyResponse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveyResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SurveyResponse::factory(100)->create();
    }
}
