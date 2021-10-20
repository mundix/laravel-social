<?php

namespace App\Http\Livewire\Events;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileEventsEditLoopComponent extends Component
{
	use WithFileUploads;

	public $event;
	protected $listeners = ['refreshProfileEventEditLoop' => 'render', 'removeEvent' => 'deleteEvent'];

	public $event_name, $event_participants, $event_description, $event_global_amount, $event_total_amount, $event_due_date,
		$event_profile_picture, $events, $sponsors;

	public function render()
	{
		return view('livewire.events.profile-events-edit-loop-component');
	}

	public function mount()
	{
		$this->event_name = $this->event->name;
		$this->event_participants = $this->event->participants;
		$this->event_description = $this->event->description;
		$this->event_total_amount = $this->event->total_amount;
		$this->event_global_amount = $this->event->global_amount;
		$this->event_due_date = $this->event->due_date->format('Y-m-d');
		$this->event_profile_picture = $this->event->Profile->url ?? '';
		$this->sponsors = $this->event->sponsors ?? [];
	}

	public function updatedEventName()
	{
		$this->validate([
			'event_name' => 'required',
		]);

		$event = $this->event;
		$event->name = $this->event_name;
		$event->save();
	}

	public function updatedEventProfilePicture()
	{
		$this->validate([
			'event_profile_picture' => 'image|max:3072',
		]);

		$event = $this->event;

		$event_file_name = 'event_profile_' . $event->id . ' . ' . $this->event_profile_picture->getClientOriginalExtension();
		$event->addMedia($this->event_profile_picture->getRealPath())->usingName($event_file_name)->toMediaCollection('profile');
		$this->event_profile_picture = $event->profile->url;
		session()->flash('success', trans('users.onboarding.profile.background_picture.success'));
		$this->emit('featuredEvents');
	}

	public function deleteEvent($eventId = null)
	{
		if(!is_null($eventId))
		{
			$event = Event::find($eventId);
			if($event)
				$event->delete();
			$this->emit('successEventDeleted', true);
			$this->emit('featuredEvents');
		}
	}

}
