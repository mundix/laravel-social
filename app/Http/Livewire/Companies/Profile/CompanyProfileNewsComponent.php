<?php

namespace App\Http\Livewire\Companies\Profile;

use Livewire\Component;

class CompanyProfileNewsComponent extends Component
{
    public $company;

    public function render()
    {
        return view('livewire.companies.profile.company-profile-news-component', [
            'posts' => $this->company->user->posts->where('status', 'publish')->take(3)
        ]);
    }
}
