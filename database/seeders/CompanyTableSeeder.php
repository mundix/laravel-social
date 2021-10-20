<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $user = User::create([
            'email' => 'company@bondeed.com',
            'email_verified_at' => now(),
            'password' => \Hash::make('12345'),
            'type' => 'company',
            'confirmed' => 'approved',
            'status_id' => 3,
            'instagram' => 'https://www.instagram.com/create_ape/',
            'twitter' => 'https://twitter.com/CreateApe',
            'facebook' => 'https://www.facebook.com/CreateApe',
            'linkedin' => 'https://www.linkedin.com/company/createape/',
            'remember_token' => Str::random(10),
        ]);
        $user->company()->save(Company::create([
            'name' => 'Pollos Hermanos LLC',
            'description' => $faker->paragraph,
            'location' => $faker->city,
            'caption' => $faker->jobTitle,
            'status' => 'enabled',
            'about' => $faker->text,
        ]));
    }
}
