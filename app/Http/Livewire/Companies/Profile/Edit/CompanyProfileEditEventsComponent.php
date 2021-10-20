<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\CompanyService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditEventsComponent extends Component
{

    use WithPagination;

    public $company;
    public $employee;
    public $searchQuery = '';
    public $category = 'all';
    private $pageName = 'events';
    public $isEmployee = false;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['companyProfileEditEventsComponent' => '$refresh'];

    public function render()
    {
        $events = $this->company->user
            ->events()
            ->where('status', '<>','draft')
            ->when($this->employee, function ($query) {
                $query
                    ->where('status', 'enabled');
            })
            ->orderBy('id', 'DESC')
            ->paginate(config('bondeed.frontend.dashboards.limit'), ['*'], $this->pageName);
        return view('livewire.companies.profile.edit.company-profile-edit-events-component', [
            'events' => $events,
            'total_draft' => $this->company->user->events()->where('status', 'draft')->count() ?? 0
        ]);
    }

    public function mount($company, $employee = null)
    {

        if(auth()->check() && auth()->user()->type === 'employee') {
            $this->isEmployee = true;
        }

        $this->employee = $employee;
        $this->company = $company;
        $this->category = [];
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
