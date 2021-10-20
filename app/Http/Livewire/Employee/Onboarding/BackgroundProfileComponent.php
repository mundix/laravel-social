<?php

namespace App\Http\Livewire\Employee\Onboarding;

use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class BackgroundProfileComponent extends Component
{

	use WithFileUploads;
	use SupportUiNotification;
	use ValidatorErrorManagementTrait;

	public $cover;
	public $user;
	public $employee;

	protected $listeners = ['refreshCoverProfile' => 'render'];

    public function render()
    {
        return view('livewire.employee.onboarding.background-profile-component');
    }

	public function mount()
	{
		$this->user = auth()->user();
		$this->employee = $this->user->employee;
	}

	public function updatedCover()
	{

        $validator = \Validator::make(['cover' => $this->cover], ['cover' => 'image|max:'. config('bondeed.uploads.limits.size'),]);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

		$user = $this->user;
		$employee = $user->employee;

		$background_file_name = 'background_image_' . $user->id . ' . ' . $this->cover->getClientOriginalExtension();

		$employee->addMedia($this->cover->getRealPath())->usingName($background_file_name)->toMediaCollection('background');
		$employee = $employee->refresh();
		$this->cover = $employee->background->url;

		$this->emit('coverReady', true);
		$this->emit('refreshCoverProfile');
		$this->alert()->success(['title' => 'Your cover was updated']);
	}
}
