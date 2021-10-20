<?php

namespace App\Http\Livewire\Events;

use App\Models\Event;
use App\Models\User;
use App\Services\EmployeeService;
use Livewire\Component;

class ProfileEventSponsorsModalComponent extends Component
{
	public $event = null, $users = [], $query;

	protected $listeners = ['openEventModal' => 'setModalEvent', 'refreshEventModal' => 'render'];

	public function render()
	{
		return view('livewire.events.profile-event-sponsors-modal-component');
	}

	public function create()
	{
		$this->event = null;
	}

	public function setModalEvent(Event $event)
	{
		$this->event = $event;
		$this->users = EmployeeService::getAll();
	}

}
