<?php

namespace App\Observers;

use App\Interfaces\Users;
use App\Jobs\SentInvitesEmailJob;
use App\Mail\CompanyInviteMail;
use App\Models\CompanyInvite;
use Illuminate\Support\Facades\Mail;

class CompanyInviteObserve
{
    /**
     * Handle the company invite "created" event.
     *
     * @param  \App\Models\CompanyInvite  $companyInvite
     * @return void
     */
    public function created(CompanyInvite $companyInvite)
    {
        try {
            Mail::to($companyInvite->employee->user->email)
                ->later(5, new CompanyInviteMail($companyInvite));;
        }catch (\Exception $e){
            \Log::info('Email wasn\'t able to send ' . $e->getMessage());
        }
    }
}
