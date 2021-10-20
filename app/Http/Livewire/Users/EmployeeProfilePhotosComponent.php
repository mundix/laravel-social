<?php

namespace App\Http\Livewire\Users;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmployeeProfilePhotosComponent extends Component
{
	use WithFileUploads;

	public $photos = [], $user, $employee, $photo, $currentDeleteId;
	protected $listeners = ['refreshComponent' => '$refresh'];

	public function render()
	{
		return view('livewire.users.profile.photos');
	}

	public function mount()
	{
		$user = EmployeeService::getUser();
		$this->user = $user;
		$this->photos = EmployeeService::getPhotos();
	}

	/**
	 * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
	 */
	public function updatedPhoto()
	{
		$this->validate([
			'photo' => 'image|max:1024',
		]);
		$user = $this->user;

		$photo_file_name = 'photo_' . $user->id . ' . ' . $this->photo->getClientOriginalExtension();

		$user->employee->addMedia($this->photo->getRealPath())->usingName($photo_file_name)->toMediaCollection('photos');
		$this->photos = [];
		$this->photos = EmployeeService::getPhotos();
		$this->emit('refreshComponent');
		session()->flash('success', 'Photo Uploaded');

	}

	public function delete($id)
	{
		$this->currentDeleteId = $id;
		$this->user->employee->getMedia('photos')->where('id', $id)->first()->delete();
		$this->photos = [];
        $this->photos = $this->user->employee->getMedia('photos')->isNotEmpty() ? $this->user->employee->getMedia('photos') : [];
		$this->emit('refreshComponent');
		session()->flash('success', 'Photo Deleted');
	}

}
