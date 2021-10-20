<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Involvements;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditInvolvementsLoopComponent extends Component
{
    use SupportUiNotification;
    public $involvement;
    public $company;

    public $hour = 0;
    public $donations = 0;
    public $matches = 0;

    public function render()
    {
        return view('livewire.companies.profile.edit.involvements.company-profile-edit-involvements-loop-component');
    }

    public function mount()
    {
        $this->hour = $this->involvement->hours;
        $this->donations = $this->involvement->donations;
        $this->matches = $this->involvement->matches;
    }

    public function updated()
    {
        $this->emit('refreshCommonInvolvementDashboardComponent');
        $this->emit('refreshCompanyAdminInvolvementComponent');
    }

    public function updatedMatches($value)
    {
        $this->involvement->update(['matches' => $value]);
        $this->alert()->success(['title' => 'Your Matches have been Updated']);
    }

}
