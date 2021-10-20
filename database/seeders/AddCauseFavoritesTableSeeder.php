<?php

namespace Database\Seeders;

use App\Services\CauseService;
use App\Services\EmployeeService;
use Illuminate\Database\Seeder;

use Faker\Generator as Faker;

class AddCauseFavoritesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(Faker $faker)
	{
		$users = EmployeeService::getAll();
		foreach (CauseService::getCauses() as $cause) {
			$user = $faker->randomElement($users);
			$user->favorite($cause);
		}
	}
}
