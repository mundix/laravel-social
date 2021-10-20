<?php

namespace Database\Seeders;

use App\Models\Company;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

use Faker\Generator as Faker;

class AddInfoToFirstCompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $company = Company::first();
	    print "\e[34m" . "Company Image Profile  \n";
	    $url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
	    $company->addMediaFromUrl($url)->toMediaCollection('profile');
	    sleep(1);
	    $url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
	    $company->addMediaFromUrl($url)->toMediaCollection('background');
	    sleep(1);
	    print "*";
	    print "\e[32m Done!! \n";

	    print "\e[31m Importing Images: \e[32m";
	    for ($i = 0; $i <= rand(3, 6); $i++) {
		    $url = 'https://source.unsplash.com/random/' . \Arr::random(['1920x1080', '1080x1920', '1080x1080']);
		    $company->addMediaFromUrl($url)->toMediaCollection('photos');
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
	    $company->addMediaFromUrl($video->videos->tiny->url)->toMediaCollection('video');
	    print "\e[32m Done! \n";

	    print "\e[31m Tags creating: ";
	    for ($i = 0; $i <= rand(3, 10); $i++) {
		    $company->user->attachTag($faker->streetName);
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


    }
}
