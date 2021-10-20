<?php

namespace App\Http\Livewire\Companies\Admin\Involvements;

use App\Models\Involvement;
use App\Models\User;
use Livewire\Component;

class CompanyAdminInvolvementComponent extends Component
{
    public $user;
    public $company;
    public $hours = 0;
    public $donations = 0;
    public $matches = 0;
    public $contributions = 0;

    protected $listeners = ['refreshCompanyAdminInvolvementComponent' => 'setValues'];

    public function render()
    {
        return view('livewire.companies.admin.involvements.company-admin-involvement-component');
    }

    public function mount()
    {
        $this->setValues();
    }

    public function setValues()
    {
        $this->hours = $this->company->involvements->sum('hours');
        $this->donations = $this->company->involvements->sum('donations');
        $this->matches = $this->company->involvements->sum('matches');
        $this->contributions = $this->donations + $this->matches;
    }
}
