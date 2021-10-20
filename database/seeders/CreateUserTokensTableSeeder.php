<?php

namespace Database\Seeders;

use App\LoadingHelper;
use App\Models\Company;
use App\Models\CompanyInvite;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserToken;
use App\Services\EmployeeService;
use App\Services\GlobalService;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use GuzzleHttp\Client;

class CreateUserTokensTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(Faker $faker)
	{

		$company = Company::first();
		$users = User::factory(2)->hasEmployee()
			->create(
				[
					'type' => 'employee',
					'instagram' => 'https://www.instagram.com/create_ape/',
					'twitter' => 'https://twitter.com/CreateApe',
					'facebook' => 'https://www.facebook.com/CreateApe',
					'linkedin' => 'https://www.linkedin.com/company/createape/',
					'status_id' => 3,
					'confirmed' => 'pending',
					'accept_agreements' => true,
				]
			);
		foreach ($users as $key => $user) {
			$employee = $user->employee;
			print "\e[34m" . "Employee #" . ($key + 1) . " \n";

			print "\e[31m Importing Images: \e[32m";
			for ($i = 0; $i <= rand(3, 6); $i++) {
				$url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
				$employee->addMediaFromUrl($url)->toMediaCollection('photos');
				sleep(1);
				print "*";
			}
			for ($count = $i; $count >= 0; $count--) {
				print "\x08";
			}
			print "\e[32m Done!! \n";

			print "\e[31m Importing Video:";
			$client = new Client();
			$res = $client->request('GET',
				'https://pixabay.com/api/videos/?key=15210871-a25245f26739f3fec727bad49&q=yellow+flowers&pretty=true', [
					'headers' => [
						'Accept' => 'application/json',
						'Content-type' => 'application/json'
					]
				]);

			$result = json_decode($res->getBody()->getContents());
			$video = collect($result->hits)->random();
			$employee->addMediaFromUrl($video->videos->tiny->url)->toMediaCollection('video');
			print "\e[32m Done! \n";

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
			}for ($count = $i; $count >= 0; $count--) {
				print "\x08";
			}
			print "\e[32m Done! \n";

            # Inviting Employees
			print "\e[31m Preparing Invitation: \e[32m" . "creating";
			$token = GlobalService::generateToken();

			$employee->user->user_token()->save(UserToken::create(['token' => $token]));
			$company->invites()->save(CompanyInvite::create([
				'employee_id' => $employee->id,
				'company_id' => $company->id
			]));
			$company->employees()->attach($employee->id);

			for ($count = strlen('creating'); $count >= 0; $count--) {
				print "\x08";
				print "";
			}
			for ($count = strlen('creating'); $count >= 0; $count--) {
				print "\x7F";
				print "";
			}
			for ($count = strlen('creating'); $count >= 0; $count--) {
				print "\x08";
				print "";
			}
			print "\e[32m" . " send!\n";
		}
	}

}
