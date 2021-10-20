<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileEditProfilePictureComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    protected $listeners = ['employeeProfileEditProfilePictureComponent' => '$refresh'];

    public $picture;
    public $currentPicture;
    public $employee;

    public function render()
    {
        return view('livewire.employees.profile.edit.employee-profile-edit-profile-picture-component');
    }

    public function mount()
    {
        $this->currentPicture = $this->employee->profile->url ?? null;
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPicture()
    {
        $validator = \Validator::make(['picture' => $this->picture], ['picture' => 'image']);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $file_name = 'profile_' . $this->employee->id . '.' . $this->picture->getClientOriginalExtension();
        $this->employee->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('profile');

        $this->employee = $this->employee->refresh();
        $this->currentPicture = $this->employee->profile->url;
        $this->alert()->success(['title' => 'Company Profile Picture was updated']);
        $this->emit('employeeProfileEditProfilePictureComponent');
    }
}
