<?php

namespace App\Http\Livewire\Employees\Profile;

use Livewire\Component;

class EmployeeProfileActivitiesComponent extends Component
{
    public $activities;
    public $company;
    public $employee;

    public function render()
    {
        return view('livewire.employees.profile.employee-profile-activities-component', [
            'activities' => $this->activities
        ]);
    }

    public function mount($employee = null, $company = null)
    {
        $this->activities = collect([]);
        if (auth()->check() &&  $employee->user_id == auth()->user()->id) {
            $this->activities = auth()->user()->activitiesRecords;
        } else {

            if (!is_null($employee)) {
                $this->activities = $employee->user->activitiesRecords;
            }

        }
    }


}
