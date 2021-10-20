<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Models\Employee;
use App\Models\Event;
use App\Services\CompanyService;
use Livewire\Component;

class CompanyProfileEditEventSponsorsModalComponent extends Component
{
    public $company;
    public $sponsors;
    public $event;
    public $searchQuery;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    protected $listeners = [
        'CompanyProfileEditEventSponsorsModalComponent' => 'setSponsors',
        'renderCompanyProfileEditEventSponsorsModalComponent' => 'render',
        'refreshCompanyProfileEditEventSponsorsModalComponent' => '$refresh',
    ];

    public function mount()
    {
        $this->sponsors = collect([]);
    }

    public function render()
    {
        $employees = (new CompanyService)->getActiveEmployeesByCompany($this->company, $this->searchQuery);
        return view('livewire.companies.profile.edit.events.company-profile-edit-event-sponsors-modal-component', [
            'employees' => $employees,
            'sponsors' => $this->sponsors
        ]);
    }

    public function updateSponsors($userId, $checked)
    {
        if (!$checked) {
            $key = $this->sponsors->search($userId);
            $this->sponsors->pull($key);
            $this->sponsors = $this->sponsors->values();
            return;
        }

        $this->sponsors->push($userId);
    }

    public function setSponsors($sponsors, $event )
    {

        $obj = Event::find($event['id']);
        $this->event = $obj;
        $this->sponsors = collect($sponsors);
        $this->emit('updateDOM');
    }

    public function save()
    {
        if ($this->event) {
            $this->event->sponsors()->sync($this->sponsors->toArray());
            $this->emit('CompanyProfileEditUpdateEventModalComponent');
        } else {
            $this->emit('CompanyProfileEditAddEventModalComponentSponsorChange', $this->sponsors->toArray());
        }
    }
}
