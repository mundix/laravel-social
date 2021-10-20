<?php

namespace App\Http\Livewire\Companies\Profile\MainInfo;

use Livewire\Component;

class CompanyProfileMainInfoUpcomingEventsComponent extends Component
{
    public $company;

    public function render()
    {
        return view('livewire.companies.profile.main-info.company-profile-main-info-upcoming-events-component', [
            'events' => $this->company->user->events()->where('status', 'enabled')->get()
        ]);
    }
}
