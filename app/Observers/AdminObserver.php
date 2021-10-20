<?php

namespace App\Observers;

use App\Mail\InviteAdminEmail;
use App\Models\Admin;

class AdminObserver
{
    /**
     * Handle the Admin "created" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function created(Admin $admin)
    {
        $password = \Str::random(8);
        $admin->user->update(['password' => \Hash::make($password)]);
        \Mail::to($admin->user->email)->send(new InviteAdminEmail($admin, $password));
    }

}
