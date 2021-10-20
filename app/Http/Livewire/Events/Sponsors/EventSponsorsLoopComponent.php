<?php

namespace App\Http\Livewire\Events\Sponsors;

use App\Services\EmployeeService;
use Livewire\Component;
use App\Models\Event;
use App\Models\User;

/**
 * This component gets Event's Sponsors
 *
 */
class EventSponsorsLoopComponent extends Component
{

    public $sponsors, $event, $users;

    protected $listeners = [
        'refreshComponent' => 'render',
        'saveSponsors' => 'save',
        'removeSponsors' => 'delete'
    ];

    public function render()
    {
        $this->sponsors = $this->event->sponsors;
        return view('livewire.events.sponsors.event-sponsors-loop-component');
    }


    public function mount($sponsors, $event)
    {
        $this->sponsors = $sponsors ?? [];
        $this->event = $event;
        $this->users = EmployeeService::getAll();
    }

    /**
     * Set All Sponsors from user id
     * @param string $userId
     */
    public function setSponsors($userId)
    {
        $this->selectedUsers[] = $userId;
    }

    /**
     * Delete Sponsor from event
     * @param string
     */
    public function delete($userId)
    {
        $this->event->sponsors()->detach($userId);
        $this->emit('refreshComponent');
    }

    /**
     * Save a selected sponsors
     * @param string $selectedUsers
     * @param int $eventID
     */
    public function save($selectedUsers, $eventID)
    {
        $event = Event::findOrFail($eventID);
        $event->sponsors()->sync([]);
        $event->sponsors()->sync($selectedUsers);
        $this->emit('successSponsorSync', true);
        $this->emit('refreshComponent');
        session()->flash('success', 'Sponsors was assigned correctly');
    }

}
