<?php

namespace Database\Seeders;

use App\Models\CategoryPost;
use Illuminate\Database\Seeder;

class CreateCategoryPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Schema::disableForeignKeyConstraints();
        CategoryPost::truncate();
        \Schema::enableForeignKeyConstraints();
        $categories = [
            ['name' => 'Arts, Culture & Humanities'],
            ['name' => 'Education & Research'],
            ['name' => 'Environment & Animals'],
            ['name' => 'Faith'],
            ['name' => 'Health'],
            ['name' => 'Human Services'],
            ['name' => 'International'],
            ['name' => 'Other'],
            ['name' => 'Public, Social Benefit'],
        ];

        foreach($categories as $items)
        {
            CategoryPost::create($items);
        }
    }
}
