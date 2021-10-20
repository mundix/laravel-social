<?php

namespace App\Http\Livewire\Employees\Profile;

use Livewire\Component;

class EmployeeProfileEventsComponent extends Component
{
    public $company;
    public $employee;

    public function render()
    {
        return view('livewire.employees.profile.employee-profile-events-component', [
            'events' => $this->company->user->events()->where('status', 'enabled')->get()
        ]);
    }

    public function mount($company = null , $employee = null )
    {
        $this->company = $company;
        $this->employee = $employee;
    }
}
