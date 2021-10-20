<?php

namespace App\Http\Livewire\Employees\Profile\Edit\Involvements;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class EmployeeProfileEditInvolvementLoopComponent extends Component
{
    use SupportUiNotification;
    public $involvement;

    public $hour = 0;
    public $donations = 0;
    public $matches = 0;

    public function render()
    {
        return view('livewire.employees.profile.edit.involvements.employee-profile-edit-involvement-loop-component');
    }

    public function mount()
    {
        $this->hour = $this->involvement->hours;
        $this->donations = $this->involvement->donations;

    }

    public function updated()
    {
        $this->emit('refreshCommonInvolvementDashboardComponent');
    }

    public function updatedHour($value)
    {
        $this->involvement->update(['hours' => $value]);
        $this->alert()->success(['title' => 'Volunteers Hours updated ']);
    }

    public function updatedDonations($value)
    {
        $this->involvement->update(['donations' => $value]);
        $this->alert()->success(['title' => 'Donations updated to ']);
    }
}
