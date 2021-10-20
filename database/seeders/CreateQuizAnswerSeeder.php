<?php

namespace Database\Seeders;

use App\Models\QuizAnswer;
use Illuminate\Database\Seeder;

class CreateQuizAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        QuizAnswer::truncate();
        QuizAnswer::create(['name' => 'Inspirer']);
        QuizAnswer::create(['name' => 'Builder']);
        QuizAnswer::create(['name' => 'Advocate']);
        QuizAnswer::create(['name' => 'Mover & Shaker', 'slug' => 'mover']);
        QuizAnswer::create(['name' => 'Interactive']);
        QuizAnswer::create(['name' => 'Servant Leader', 'slug' => 'servant']);
        QuizAnswer::create(['name' => 'Connector']);
    }
}
