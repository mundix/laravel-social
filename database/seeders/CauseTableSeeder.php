<?php

namespace Database\Seeders;

use App\Models\Cause;
use App\Models\User;
use App\Services\CauseService;
use App\Services\CompanyService;
use App\Services\EmployeeService;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;


class CauseTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(Faker $faker)
	{
		$causes = Cause::factory(10)
			->create([
				'user_id' => $faker->randomElement(CompanyService::getAllPluck()),
				'category_id' => $faker->randomElement(CauseService::getCategoriesPluck())
			]);
		print "\e[31m Importing Cause media : \e[32m";
		foreach ($causes as $cause) {
			$url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
			$cause->addMediaFromUrl($url)->toMediaCollection('picture');
			sleep(1);
			print "*";
		}
		for($i=30; $i >=0 ; $i--)
			print "\x08";
		for($i=30; $i >=0 ; $i--)
			print "\x7F";
		for($i=30; $i >=0 ; $i--)
			print "\x08";
		print "\e[32m Done! \n";
	}
}
