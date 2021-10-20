<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CreateAdminTableSeeder::class,
            CreateUserStatusTableSeeder::class,
            CreateCausesCategoriesTableSeeder::class,
            CreateCategoryPostSeeder::class,
            CreateQuizAnswerSeeder::class,
        ]);
    }
}
