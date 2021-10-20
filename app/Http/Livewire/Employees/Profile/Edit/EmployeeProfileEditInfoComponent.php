<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;

class EmployeeProfileEditInfoComponent extends Component
{

    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $first_name;
    public $last_name;
    public $location;
    public $description;
    public $email;
    public $employee;

    public function render()
    {
        return view('livewire.employees.profile.edit.employee-profile-edit-info-component');
    }

    public function mount()
    {
        $this->first_name = $this->employee->first_name;
        $this->last_name = $this->employee->last_name;
        $this->location = $this->employee->location;
        $this->email = $this->employee->user->email;
        if (!empty(trim($this->employee->description))) {
            $this->description = $this->employee->description;
        } else {
            $this->description = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec sagittis arcu. Ut ut auctor lacus. Sed sed finibus magna. Phasellus ac ultricies lorem, non venenatis mauris. Phasellus finibus posuere massa, et facilisis ante bibendum ac. Suspendisse potenti.

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin nec sagittis arcu. Ut ut auctor lacus. Sed sed finibus magna. Phasellus ac ultricies lorem, non venenatis mauris. Phasellus finibus posuere massa, et facilisis ante bibendum ac. Suspendisse potenti.';
        }
    }

    public function updatedFirstName($value)
    {
        $validator = \Validator::make([
            'first_name' => $value,
        ], ['first_name' => 'required']);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->employee->update(['first_name' => $this->first_name]);

        $this->alert()->success([
            'title' => 'Employee First name  updated'
        ]);
    }

    public function updatedLastName($value)
    {
        $validator = \Validator::make([
            'last_name' => $value,
        ], ['last_name' => 'required']);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->employee->update(['last_name' => $this->first_name]);

        $this->alert()->success([
            'title' => 'Employee Last name  updated'
        ]);
    }

    public function updatedLocation($value)
    {
        $validator = \Validator::make([
            'location' => $value,
        ], ['location' => 'required']);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $this->employee->update(['location' => $this->location]);

        $this->alert()->success([
            'title' => 'Location updated'
        ]);
    }

    public function updatedDescription($value)
    {
        $validator = \Validator::make([
            'description' => $value,
        ], ['description' => 'required|max:800']);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }
        $this->employee->update(['description' => $this->description]);

        $this->alert()->success([
            'title' => 'Employee Description updated'
        ]);
    }
}
