<?php

namespace App\Http\Livewire\Companies\Admin\Events;

use App\Services\EventService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyAdminEventsDashboardComponent extends Component
{

    use WithPagination;

    public $company;
    public $searchQuery = '';
    public $status = null;
    public $disabled = null;
    public $sortField = 'name';
    public $sortAsc = true;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'companyProfileEditEventsComponent' => '$refresh',
        'companyAdminEventsDashboardComponent' => 'render',
        'refreshCompanyAdminEventsDashboardComponent' => '$refresh',
        'renderCompanyAdminEventsDashboardComponent' => 'render',
    ];

    public function render()
    {
        $events = (new EventService)
            ->search(
                $this->company,
                $this->searchQuery,
                $this->status,
                $this->disabled,
                config('bondeed.frontend.dashboards.limit-10'),
                $this->sortField,
                $this->sortAsc
            );
        return view('livewire.companies.admin.events.company-admin-events-dashboard-component', [
            'events' => $events,
            'total_draft' => $this->company->user->events()->where('status', 'draft')->count() ?? 0
        ]);
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

    public function hydrate()
    {
        $this->emit('updateDOM');
    }
}
