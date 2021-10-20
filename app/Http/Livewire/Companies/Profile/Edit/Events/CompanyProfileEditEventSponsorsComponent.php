<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Models\Employee;
use App\Models\User;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditEventSponsorsComponent extends Component
{
    use SupportUiNotification;

    public $company;
    public $sponsors;

    protected $listeners = [
        'CompanyProfileEditEventSponsorsComponentUpdateSponsors' => 'updateSponsors',
        'CompanyProfileEditEventSponsorsComponentClear' => 'clearSponsors',
        'deleteSponsor' => 'deleteSponsor'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.events.company-profile-edit-event-sponsors-component');
    }

    public function updateSponsors($sponsors)
    {
        $this->sponsors = User::whereIn('id', $sponsors)->get();
    }

    public function deleteSponsorAction($employeeId)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to remove this sponsor ?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteSponsor',
            'params' => $employeeId
        ]);
    }

    public function deleteSponsor($employeeId)
    {
        $this->sponsors = $this->sponsors->filter(function(User $user) use ($employeeId){
            return $user->id != $employeeId;
        });

        $this->emit('renderCompanyProfileEditEventSponsorsModalComponent');
        $this->emit('refreshCompanyProfileEditEventSponsorsModalComponent');
        $this->emit('renderCompanyAdminEventNewSponsorsModalComponent');
        $this->emit('refreshCompanyAdminEventNewSponsorsModalComponent');

        $this->emit('CompanyProfileEditAddEventModalComponentSponsorChange', $this->sponsors->pluck('id')->toArray());
    }

    public function clearSponsors()
    {
        $this->sponsors = [];
    }
}
