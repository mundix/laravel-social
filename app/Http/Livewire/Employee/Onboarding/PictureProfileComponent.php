<?php

namespace App\Http\Livewire\Employee\Onboarding;

use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class PictureProfileComponent extends Component
{

	use WithFileUploads;
	use SupportUiNotification;
    use ValidatorErrorManagementTrait;

	public $picture;
	public $user;
	public $employee;

	protected $listeners = ['pictureProfileComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.employee.onboarding.picture-profile-component');
    }

    public function mount()
    {
		$this->user = auth()->user();
		$this->employee = $this->user->employee;
    }

    public function updatedPicture()
    {
        $validator = \Validator::make(['picture' => $this->picture], ['picture' => 'image|max:'. config('bondeed.uploads.limits.size'),]);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

	    $user = $this->user;
	    $employee = $user->employee;

	    $file_name = 'profile_picture_' . $user->id . '.' . $this->picture->getClientOriginalExtension();

	    $employee->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('profile');
        $employee = $employee->refresh();
	    $this->picture = $employee->profile->url;

	    $this->emit('pictureReady', true);
	    $this->emit('pictureProfileComponent');
        $this->alert()->success(['title' => 'Your profile picture was updated']);
    }

}
