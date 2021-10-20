<?php

namespace App\Http\Livewire\Companies\Profile;

use Livewire\Component;

class CompanyProfileStoriesComponent extends Component
{
    public $company;
    public $showActions = false;

    public function mount()
    {
        if(\Auth::user() && \Auth::user()->type === 'company' && $this->company === \Auth::user()->company->id) {
            $this->showActions = true;
        }
    }

    protected $listeners = ['companyStoriesComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.companies.profile.company-profile-stories-component', [
            'stories' => $this->company->stories()->whereStatus('publish')->get(),
        ]);
    }
}
