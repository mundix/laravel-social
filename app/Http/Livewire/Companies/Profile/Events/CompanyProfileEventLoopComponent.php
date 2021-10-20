<?php

namespace App\Http\Livewire\Companies\Profile\Events;

use Livewire\Component;

class CompanyProfileEventLoopComponent extends Component
{
    public $event;
    public $employee;

    public function render()
    {
        return view('livewire.companies.profile.events.company-profile-event-loop-component');
    }

    public function mount($employee = null)
    {
        $this->employee = $employee;
    }
}
