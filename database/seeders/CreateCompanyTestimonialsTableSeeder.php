<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Testimonial;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Database\Seeder;

class CreateCompanyTestimonialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	\Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
    	Testimonial::truncate();
	    \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        $testimonials = Testimonial::factory(20)->create([
            'company_id' => Company::first()->id,
        ]);
	    print "\e[31m Add Media to Testimonial: \e[32m";
		foreach ($testimonials as $testimonial)
		{
			$url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
			$testimonial->addMediaFromUrl($url)->toMediaCollection('picture');
			sleep(1);
			print "*";
		}
	    for ($count = 20; $count >= 0; $count--) {
		    print "\x08";
	    }
	    print "\e[32m Done!! \n";
    }
}
