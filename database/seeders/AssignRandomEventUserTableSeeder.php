<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

use Faker\Generator as Faker;

class AssignRandomEventUserTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(Faker $faker)
	{
		print "\e[31m" . "Assign Sponsors:";
		$events = Event::all();
		$total_event = Event::count();
		for ($i = 0; $i < $total_event - 1; $i++) {
			$index = $faker->numberBetween(0, $total_event - 1);
			$event = $events[$index];
			$event->sponsors()->attach($faker->randomElement(User::whereType('employee')->pluck("id")));
		}
		print "\e[33m" . " Done!\n";
	}
}
