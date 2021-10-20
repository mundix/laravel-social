<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

use Faker\Generator as Faker;

class CreateCompanyEmployees extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $company = Company::first();
        $user = $company->user;

	    $users = User::factory(5)->hasEmployee()
		    ->create(
			    [
				    'type' => 'employee',
				    'instagram' => 'https://www.instagram.com/create_ape/',
				    'twitter' => 'https://twitter.com/CreateApe',
				    'facebook' => 'https://www.facebook.com/CreateApe',
				    'linkedin' => 'https://www.linkedin.com/company/createape/',
				    'status_id' => 3,
				    'confirmed' => 'approved',
				    'accept_agreements' => true,
			    ]
		    );
		foreach ($users as $user)
		{
			$employee = $user->employee;

			print "\e[31m Creating Media Profile: \e[32m";

			$url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
			$employee->addMediaFromUrl($url)->toMediaCollection('profile');
			print "*";

			$url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
			$employee->addMediaFromUrl($url)->toMediaCollection('background');
			sleep(10);
			print "*";

			for ($count = 2; $count >= 0; $count--) {
				print "\x08";
			}

			print "\e[32m Done!! \n";

			print "\e[31m Tags creating: ";
			for ($i = 0; $i <= rand(3, 10); $i++) {
				$employee->user->attachTag($faker->streetName);
				print "\e[32m" . "*";
			}
			for ($count = $i; $count >= 0; $count--) {
				print "\x08";
			}
			for ($count = $i; $count >= 0; $count--) {
				print "\x7F";
			}
			for ($count = $i; $count >= 0; $count--) {
				print "\x08";
			}

			print "\e[32m Done! \n";

	        $company->employees()->attach($employee->id);
	    }
	}
}
