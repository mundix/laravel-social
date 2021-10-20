<?php

namespace App\Http\Livewire\Users;

use App\Models\Employee;
use App\Services\EmployeeService;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileVideoComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;

    public $video;
    public  $user;
    protected $listeners = ['employeeProfileVideoComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.users.profile.video');
    }

    public function mount()
    {
        $user = EmployeeService::getUser();
        $this->user = $user;
        $this->video = $this->user->employee->video->url;

    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedVideo()
    {
        $this->validate([
            'video' => 'mimetypes:video/mp4|max:2000000'
        ]);
        $user = $this->user;
        $employee = $user->employee;

        $video_file_name  = 'profile_video_' . $user->id . ' . ' . $this->video->getClientOriginalExtension();

        $employee->addMedia( $this->video->getRealPath())->usingName( $video_file_name)->toMediaCollection( 'video');
        $employee = $employee->refresh();

        $this->video = $employee->video->url;
        $this->alert()->success(['title' => 'Video uploaded']);
    }

    public function remove()
    {
    	$user = $this->user;
    	$video = $user->employee->getMedia("video")->first();
    	$video->delete();
	    $this->video = null;
    }
}
