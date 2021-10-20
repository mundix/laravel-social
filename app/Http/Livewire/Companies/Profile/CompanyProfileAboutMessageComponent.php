<?php

namespace App\Http\Livewire\Companies\Profile;

use Livewire\Component;

class CompanyProfileAboutMessageComponent extends Component
{
    public $company;

    public function render()
    {
        return view('livewire.companies.profile.company-profile-about-message-component');
    }
}
