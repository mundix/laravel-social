<?php

namespace App\Http\Livewire\Events;

use Livewire\Component;

class UpcomingEventsComponent extends Component
{

	public $events;

    public function render()
    {
        return view('livewire.events.upcoming-events-component');
    }

    public function mount()
    {
    	$this->events = $this->company->events;
    }
}
