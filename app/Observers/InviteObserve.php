<?php

namespace App\Observers;

use App\Mail\Admin\AdminInviteEmail;
use App\Models\Invite;
use App\Traits\SupportUiNotification;
use Illuminate\Support\Facades\Mail;

class InviteObserve
{
    /**
     * Handle the Invite invite "created" event.
     *
     * @param  \App\Models\CompanyInvite  $companyInvite
     * @return void
     */
    public function created(Invite $invite)
    {
        try {
            Mail::to($invite->email)->send(new AdminInviteEmail($invite));
        }catch (\Exception $e){
            \Log::info('Email wasn\'t able to send ' . $e->getMessage());
        }
    }
}
