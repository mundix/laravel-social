<?php

namespace App\Http\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileEditEventsComponent extends Component
{
	use WithFileUploads;

	public $name, $participants, $description, $global_amount, $total_amount, $due_date,
		$status_savings = true, $user, $file, $profile_picture = null, $events, $hasProfile = false, $event;

	protected $listeners = ['featuredEvents' => 'render'];

	public function render()
	{
		return view('livewire.events.profile-edit-events-component');
	}

	public function mount()
	{
		$this->user = auth()->user();
		$this->events = $this->user->events;
		$this->event = new Event();
		$this->profile_picture = asset('/assets/images/image-placeholder.svg');
		$this->hasProfile = false;
	}

	public function createEvent()
	{
		$this->status_savings = false;

		$validate = $this->validate([
			'name' => 'required',
			'description' => 'required',
			'global_amount' => 'required',
			'total_amount' => 'required',
			'due_date' => 'required',
			'participants' => 'required|min:1',
		]);
		$data = [
			'name' => $this->name,
			'description' => $this->description,
			'global_amount' => $this->global_amount,
			'total_amount' => $this->total_amount,
			'due_date' => $this->due_date,
			'participants' => $this->participants
		];

		$event = $this->event;
		$event->fill($data);
		$this->user->events()->save($event);

		if ($this->file) {

			$event_file_name = 'event_profile_' . $event->id . ' . ' . $this->file->getClientOriginalExtension();
			$event->addMedia($this->file->getRealPath())->usingName($event_file_name)->toMediaCollection('profile');
		}

		$this->status_savings = true;
		$this->resetFields();
		$this->emit('featuredEvents');
	}

	public function updatedProfilePicture()
	{
		$this->validate([
			'profile_picture' => 'image|max:3072',
		]);
		$this->file = $this->profile_picture;
		$this->profile_picture = $this->profile_picture->temporaryUrl();
		$this->hasProfile = true;
	}

	private function resetFields()
	{
		$this->name = '';
		$this->description = '';
		$this->global_amount = '';
		$this->total_amount = '';
		$this->due_date = '';
		$this->participants = '';
		$this->profile_picture = asset('/assets/images/image-placeholder.svg');
	}


}
