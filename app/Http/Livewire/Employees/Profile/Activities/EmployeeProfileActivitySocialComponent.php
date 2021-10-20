<?php

namespace App\Http\Livewire\Employees\Profile\Activities;

use App\Models\Activity;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class EmployeeProfileActivitySocialComponent extends Component
{
    use SupportUiNotification;

    public $employee;
    public $company;

    public function render()
    {
        return view('livewire.employees.profile.activities.employee-profile-activity-social-component');
    }

    public function mount($company = null , $employee = null)
    {
        $this->employee = $employee;
        $this->company = $company;
    }

    public function thank()
    {
        $user = auth()->user();
        $activity = new Activity(['user_id' => $user->id, 'type' =>'thank']);
        $this->employee->user->activities()->save($activity);
        $this->alert()->success(['title' => 'You Send a Thank You to ' . $this->employee->fullname]);

    }

    public function kudo()
    {
        $user = auth()->user();
        $activity = new Activity(['user_id' => $user->id, 'type' =>'kudo']);
        $this->employee->user->activities()->save($activity);
        $this->alert()->success(['title' => 'Gave Kudos to ' . $this->employee->fullname]);
    }
}

