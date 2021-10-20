<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\User;

class UserObserve
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return voidq
     */
    public function created(User $user)
    {
        if($user->type === 'company') {
           $company = new Company();
           $company->user_id = $user->id;
        }
    }

}
