<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileEditCoverComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    protected $listeners = ['employeeProfileEditCoverComponent' => '$refresh'];

    public $employee;
    public $cover;
    public $currentCover;
    public $user;

    public function render()
    {
        return view('livewire.companies.profile.edit.company-profile-edit-cover-component');
    }

    public function mount()
    {
        $this->currentCover = $this->employee->background->url;
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedCover()
    {

        $validator = \Validator::make(['cover' => $this->cover], ['cover' => 'image']);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $file_name = 'background_' . $this->employee->id . '.' . $this->cover->getClientOriginalExtension();
        $this->employee->addMedia($this->cover->getRealPath())->usingName($file_name)->toMediaCollection('background');
        $this->employee = $this->employee->refresh();
        $this->currentCover = $this->employee->background->url;
        $this->alert()->success(['title' => 'Updated Employee Cover Picture']);
        $this->emit('employeeProfileEditCoverComponent');
    }
}
