<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserStatus;

class CreateUserStatusTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\Schema::disableForeignKeyConstraints();
		UserStatus::truncate();
		\Schema::enableForeignKeyConstraints();
		$statusArray = [
			'Draft',
			'Pending',
			'Completed',
		];

		foreach ($statusArray as $status) {
			UserStatus::create(['name' => $status]);
		}
	}
}
