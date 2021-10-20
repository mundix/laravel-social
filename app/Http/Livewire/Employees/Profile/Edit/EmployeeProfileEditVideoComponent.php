<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Services\EmployeeService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileEditVideoComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $video;
    public  $user;
    protected $listeners = [
        'EmployeeProfileEditVideoComponent' => '$refresh',
        'renderEmployeeProfileEditVideoComponent' => 'render',
        'deleteVideo' => 'doDelete'
    ];

    public function render()
    {
        return view('livewire.employees.profile.edit.employee-profile-edit-video-component');
    }

    public function mount($user)
    {
        $this->user = $user;
        $this->video = $this->user->employee->video->url;
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedVideo()
    {
        $validator = \Validator::make(['video' => $this->video],['video' => 'mimetypes:video/mp4']);
        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $employee = $this->user->employee;
        $video_file_name  = 'profile_video_' . $this->user->id . ' . ' . $this->video->getClientOriginalExtension();
        $employee->addMedia( $this->video->getRealPath())->usingName( $video_file_name)->toMediaCollection( 'video');
        $employee = $employee->refresh();
        $this->video = $employee->video->url;
        $this->alert()->success(['title' => 'Video uploaded']);

        $this->emit('EmployeeProfileEditVideoComponent');
        $this->emit('renderEmployeeProfileEditVideoComponent');
    }

    public function remove()
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to delete this?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'deleteVideo',
        ]);
    }

    public function doDelete()
    {
        $video = $this->user->employee->getMedia("video")->first();
        $video->delete();
        $this->video = null;
        $this->alert()->success(['title' => 'Video was deleted']);
        $this->emit('EmployeeProfileEditVideoComponent');
        $this->emit('updateDOM');
    }
}
