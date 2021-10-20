<?php

namespace App\Http\Livewire\Companies\Admins\Events;

use App\Models\User;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminNewEventSponsorsLoopComponent extends Component
{
    use SupportUiNotification;

    public $company;
    public $sponsors;

    protected $listeners = [
        'updateCompanyAdminNewEventSponsorsLoopComponent' => 'updateSponsors',
        'clearCompanyAdminNewEventSponsorsLoopComponent' => 'clearSponsors',
        'deleteCompanyAdminNewEventSponsorsLoopComponent' => 'deleteSponsor',
        'refreshCompanyAdminNewEventSponsorsLoopComponent' => '$refresh',
        'renderCompanyAdminNewEventSponsorsLoopComponent' => 'render'
    ];

    public function render()
    {
        return view('livewire.companies.admins.events.company-admin-new-event-sponsors-loop-component');
    }

    /**
     * This will receive the sponsors from employees list checked and sent by confirm button
     * @param array
     * @return void
    */
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
            'method' => 'deleteCompanyAdminNewEventSponsorsLoopComponent',
            'params' => $employeeId
        ]);
    }

    public function deleteSponsor($employeeId)
    {
        $this->sponsors = $this->sponsors->filter(function(User $user) use ($employeeId){
            return $user->id != $employeeId;
        });

        $this->emit('CompanyProfileEditAddEventModalComponentSponsorChange', $this->sponsors->pluck('id')->toArray());
        $this->emit('setCompanyAdminEventNewSponsorsModalComponent', $this->sponsors->pluck('id')->toArray());

        $this->emit('renderCompanyProfileEditEventSponsorsModalComponent');
        $this->emit('refreshCompanyProfileEditEventSponsorsModalComponent');
        $this->emit('renderCompanyAdminEventNewSponsorsModalComponent');
        $this->emit('refreshCompanyAdminEventNewSponsorsModalComponent');
    }

    public function clearSponsors()
    {
        $this->sponsors = [];
    }
}
