<?php

namespace Database\Seeders;

use App\Services\CauseService;
use App\Services\CompanyService;
use Illuminate\Database\Seeder;

use Faker\Generator as Faker;

class AddFavoritesCauseToCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $users = CompanyService::getAll();
        foreach (CauseService::getCauses() as $cause) {
            $user = $faker->randomElement($users);
            $user->favorite($cause);
        }
    }
}
