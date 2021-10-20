<?php

namespace App\Http\Livewire\Users;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfileBackgroundComponent extends Component
{
	use WithFileUploads;

	public $background_image, $user;

	public function render()
	{
		return view('livewire.users.profile.background');
	}

	public function mount()
	{
		$user = EmployeeService::getUser();
		$this->user = $user;
		$this->background_image = EmployeeService::getProfileBackground();
	}

	public function updatedBackgroundImage()
	{
		$this->validate([
			'background_image' => 'image|max:1024',
		]);

		$user = $this->user;
		$employee = $user->employee;

		$background_file_name = 'background_image_' . $user->id . ' . ' . $this->background_image->getClientOriginalExtension();

		$employee->addMedia($this->background_image->getRealPath())->usingName($background_file_name)->toMediaCollection('background');
		$this->background_image = $employee->background->url;
		session()->flash('success', trans('users.onboarding.profile.background_picture.success'));

	}
}
