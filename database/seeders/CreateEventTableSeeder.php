<?php

namespace Database\Seeders;

use App\Models\CategoryCause;
use App\Models\Event;
use App\Services\EmployeeService;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateEventTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(Faker $faker)
	{
		Event::where('status', 'enabled')->delete();
		\Schema::disableForeignKeyConstraints();
		Event::truncate();
		\Schema::enableForeignKeyConstraints();
		$events = Event::factory(10)
			->create([
				'user_id' => User::get()->last()->id,
			]);
		print "\e[31m" . "Importing Media to Created Events:";
		foreach ($events as $event) {
			$url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
			$event->addMediaFromUrl($url)->toMediaCollection('profile');
			sleep(1);
		}
		print "\e[33m" . " Done!\n";

	}
}
