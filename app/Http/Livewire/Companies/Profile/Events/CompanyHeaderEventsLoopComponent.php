<?php

namespace App\Http\Livewire\Companies\Profile\Events;

use Livewire\Component;

class CompanyHeaderEventsLoopComponent extends Component
{
    public $company;

    public function render()
    {
        return view('livewire.companies.events.company-header-events-loop-component');
    }
}
