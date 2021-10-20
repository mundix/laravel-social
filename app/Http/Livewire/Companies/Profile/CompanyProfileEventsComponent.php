<?php

namespace App\Http\Livewire\Companies\Profile;

use Livewire\Component;

class CompanyProfileEventsComponent extends Component
{
    public $company;
    public $showActions = false;

    public function mount()
    {
        if(\Auth::user() && \Auth::user()->type === 'company' && $this->company === \Auth::user()->company->id) {
            $this->showActions = true;
        }
    }

    protected $listeners = ['companyEventsCompany' => '$refresh'];

    public function render()
    {
        return view('livewire.companies.profile.company-profile-events-component', [
            'events' => $this->company->user->events()->where('status', 'enabled')->get()
        ]);
    }


}
