<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Services\EmployeeService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileEditPhotosComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $employee;
    public $photos = [];
    public $total = 0;
    public $photo;
    public $currentDeleteId;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'deletePhoto' => 'doDelete'
    ];

    public function render()
    {
        return view('livewire.employees.profile.edit.employee-profile-edit-photos-component');
    }


    public function mount($employee)
    {
        $this->employee = $employee;
        $this->photos = $this->employee->getMedia('photos')->isNotEmpty() ? $this->employee->getMedia('photos') : collect();
        $this->total = $this->photos->count();
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPhoto()
    {
        if($this->total > 10) {
            $this->alert()->error(['title' => 'Your gallery reached the limits of maximum photos by 10']);
            return;
        }

        $validator = \Validator::make(['photo' => $this->photo],['photo' => 'image']);

        if($validator->fails()) {

            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();

        }

        $photo_file_name = 'photo_' . $this->employee->user->id . ' . ' . $this->photo->getClientOriginalExtension();

        $this->employee->addMedia($this->photo->getRealPath())->usingName($photo_file_name)->toMediaCollection('photos');

        $this->photos = [];

        $this->photos = EmployeeService::getPhotos();

        $this->total = $this->photos->count();

        $this->alert()->success(['title' => 'Your photo was successfully uploaded ']);

        $this->emit('refreshComponent');

    }

    public function delete($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deletePhoto',
            'params' => $id
        ]);
    }

    public function doDelete($id)
    {
        $this->currentDeleteId = $id;
        $this->employee->getMedia('photos')->where('id', $id)->first()->delete();
        $this->photos = [];
        $this->employee  = $this->employee->refresh();
        $this->photos = $this->employee->getMedia('photos')->isNotEmpty() ? $this->employee->getMedia('photos') : [];
        $this->total = $this->photos->count();
        $this->alert()->success(['title' => 'Your photo was deleted']);
        $this->emit('refreshComponent');
    }
}
