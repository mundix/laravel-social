<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DeleteAllMediaDirectory extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$directory = 'public';
		print "\e[34m Media directory files: ";
		if (is_dir($directory)) {
			if (Storage::deleteDirectory($directory)) {
				print "\e[32m  Deleted \n";
			} else {
				print "\e[31m Directory not found [$directory] \n";
			}
		} else {
			print "\e[32m No Media Directory found.\n ";
		}
	}
}
