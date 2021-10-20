<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Str;

class CreateEmployeeTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$user = User::create([
			'accept_agreements' => true,
			'email' => 'ce.pichardo@gmail.com',
			'password' => \Hash::make('12345'),
			'type' => 'employee',
			'confirmed' => 'approved',
			'status_id' => 3,
			'remember_token' => Str::random(10),
			'email_verified_at' => now(),
		]);
		$user->employee()->save(Employee::create([
			'first_name' => 'Edmundo',
			'last_name' => 'Pichardo',
			'location' => 'Santiago'
		]));

		$user = User::create([
			'accept_agreements' => true,
			'email' => 'test@test.com',
			'password' => \Hash::make('12345'),
			'type' => 'employee',
			'confirmed' => 'pending',
			'status_id' => 3,
			'remember_token' => Str::random(10),
			'email_verified_at' => now(),
		]);
		$user->employee()->save(Employee::create([
			'first_name' => 'Test',
			'last_name' => 'Dummy',
			'location' => 'New Jersey'
		]));
	}
}
