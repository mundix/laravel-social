<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CreateAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::whereEmail('admin@bondeed.com')->first()) {
            $user = User::create([
                'email' => 'admin@bondeed.com',
                'email_verified_at' => now(),
                'password' => bcrypt('createpass'),
                'type' => 'super',
                'confirmed' => 'approved',
                'status_id' => 3,
                'status' => 'active',
                'instagram' => '',
                'twitter' => '',
                'facebook' => '',
                'linkedin' => '',
                'remember_token' => Str::random(10),
            ]);


            $admin = new Admin(['user_id' => $user->id, 'first_name' => 'Admin', 'last_name' => 'Bondeed']);
            $admin->save();
        }
    }
}
