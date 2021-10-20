<?php

namespace App\Http\Livewire\Companies\Profile;

use Livewire\Component;

class CompanyProfileTestimonialsComponent extends Component
{
    public $company;

    protected $listeners = [
        'CompanyProfileTestimonialsComponent' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.companies.profile.company-profile-testimonials-component', [
            'testimonials' => $this->company->testimonials
        ]);
    }
}
