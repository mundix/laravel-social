<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Models\Employee;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditEventUpdateSponsorsComponent extends Component
{
    use SupportUiNotification;

    public $event;
    public $sponsors;

    protected $listeners = [
        'setCompanyProfileEditEventUpdateSponsorsComponent' => 'setSponsor',
        'CompanyProfileEditEventUpdateSponsorsComponent' => 'updateSponsors',
        'deleteSponsorUpdateModal' => 'deleteSponsor'
    ];

    public function render()
    {

        return view('livewire.companies.profile.edit.events.company-profile-edit-event-update-sponsors-component');
    }

    public function deleteSponsorAction($employeeId)
    {
        $employee = Employee::find($employeeId);
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to remove ' . $employee->fullname . '?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteSponsorUpdateModal',
            'params' => $employeeId
        ]);
    }

    public function deleteSponsor($employeeId)
    {
        $this->sponsors = $this->sponsors->filter(function(Employee $employee) use ($employeeId){
            return $employee->id != $employeeId;
        });

        $this->emit('CompanyProfileEditAddEventModalComponentSponsorChange', $this->sponsors->pluck('id')->toArray());
    }
}
