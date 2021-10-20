<?php

namespace App\Jobs;

use App\Interfaces\Users;
use App\Mail\CompanyInviteMail;
use App\Models\CompanyInvite;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SentInvitesEmailJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $invite;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct(CompanyInvite $invite)
	{
		$this->invite = $invite;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		Mail::to($this->invite->employee->user->email)
			->send(new CompanyInviteMail($this->invite));;
	}
}
