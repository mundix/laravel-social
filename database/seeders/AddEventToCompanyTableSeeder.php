<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Event;
use Illuminate\Database\Seeder;

class AddEventToCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    print "\e[34m" . "Company Event  \n";
        $company = Company::first();
        $user = $company->user;
        $events = Event::factory(5)->create([
        	'user_id' => $user->id,
        ]);
	    print "\e[31m Creating Events: \e[32m";
        foreach($events as $event)
        {
	        $url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
	        $event->addMediaFromUrl($url)->toMediaCollection('profile');
	        sleep(1);
	        print "*";
        }
	    for ($count = 5; $count >= 0; $count--) {
		    print "\x08";
	    }
	    print "\e[32m Done!! \n";
    }
}
