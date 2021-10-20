<?php

namespace Database\Seeders;

use App\Models\Nominate;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;

class CreateCauseNominationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        Nominate::factory(5)->create();
    }
}
