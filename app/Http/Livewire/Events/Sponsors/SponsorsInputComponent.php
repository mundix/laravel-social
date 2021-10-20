<?php

namespace App\Http\Livewire\Events\Sponsors;

use Livewire\Component;

class SponsorsInputComponent extends Component
{
	public $user, $event, $checked;

	public function render()
	{
		return view('livewire.events.sponsors.sponsors-input-component');
	}

	public function mount( $user, $event)
	{
		$this->user = $user;
		$this->event = $event;
	}

}
