<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Events;

use App\Services\EventService;
use Livewire\Component;

class CompanyProfileEditApprovalEventsComponent extends Component
{

    public $company;
    public $sortField = 'name';
    public $sortAsc = true;
    public $pageName = 'approveEvents';

    protected $listeners = [
        'refreshCompanyProfileEditApprovalEventsComponent' => '$refresh',
        'renderCompanyProfileEditApprovalEventsComponent' => 'render',
    ];

    public function render()
    {
        $events = (new EventService)
            ->search(
                $this->company,
                null,
                'draft',
                null,
                config('bondeed.frontend.dashboards.limit-10'),
                $this->sortField,
                $this->sortAsc
            );

        return view('livewire.companies.profile.edit.events.company-profile-edit-approval-events-component', [
            'events' => $events
        ]);
    }

    public function mount($company)
    {
        $this->company = $company;
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
        $this->emit('updateDOM');
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }
}
