<?php

namespace App\Http\Livewire\Companies\Profile;

use Livewire\Component;

class CompanyProfileMainInfoComponent extends Component
{
    public $company;
    public $owner = false;
    public $isCompany = false;
    public $isLogged = false;

    public function render()
    {
        return view('livewire.companies.profile.company-profile-main-info-component');
    }

    public function mount()
    {
        if(\Auth::check() ) {
            $user = auth()->user();
            $this->isLogged = true;
            if( $user->id === $this->company->user->id) {
                $this->owner = true;
            }
            if( $user->type === 'company'){
                $this->isCompany = true;
            }
        }
    }
}
