<?php
/**
 * Modal with list of sponsors to sent to a new event modal
*/
namespace App\Http\Livewire\Companies\Admin\Events;

use App\Models\Event;
use App\Services\CompanyService;
use Livewire\Component;

class CompanyAdminEventNewSponsorsModalComponent extends Component
{
    public $company;
    public $sponsors;
    public $event = null;
    public $searchQuery;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    protected $listeners = [
        'updateCompanyAdminEventNewSponsorsModalComponent' => 'updateSponsors',
        'setCompanyAdminEventNewSponsorsModalComponent' => 'setSponsors',
        'renderCompanyAdminEventNewSponsorsModalComponent' => 'render',
        'refreshCompanyAdminEventNewSponsorsModalComponent' => '$refresh',
    ];

    public function render()
    {
        $employees = (new CompanyService)->getActiveEmployeesByCompany($this->company, $this->searchQuery);
        return view('livewire.companies.admin.events.company-admin-event-new-sponsors-modal-component', [
            'employees' => $employees,
            'sponsors' => $this->sponsors
        ]);
    }

    public function mount()
    {
        $this->sponsors = collect([]);
    }

    /**
     * This method send the user id of the employee to an array with ids. .
     * @param int $userId
     * @param bool $checked
     *
     * @retyrn void
    */
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

    public function setSponsors($sponsors )
    {
        $this->sponsors = collect($sponsors);
        $this->emit('refreshCompanyAdminEventNewSponsorsModalComponent');
        $this->emit('updateDOM');
    }

    /**
     * Sent sponsors to new event modal sponsors selected
     * @return void
    */
    public function save()
    {
        $this->emit('updateSponsorsCompanyProfileEditAddEventModalComponent', $this->sponsors->toArray());
    }

}
