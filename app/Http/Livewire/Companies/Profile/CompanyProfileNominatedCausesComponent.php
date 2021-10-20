<?php

namespace App\Http\Livewire\Companies\Profile;

use App\Services\CauseService;
use Livewire\Component;

class CompanyProfileNominatedCausesComponent extends Component
{

    public $company;

    protected $listeners = [
        'CompanyProfileTestimonialsComponent' => '$refresh'
    ];

    public function render()
    {
        $nominatedCauses = (new CauseService)->search(null,null,null,null,'nominate');
        return view('livewire.companies.profile.company-profile-nominated-causes-component', [
            'causes' => $nominatedCauses
        ]);
    }
}
