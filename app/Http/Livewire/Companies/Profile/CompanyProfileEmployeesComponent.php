<?php

namespace App\Http\Livewire\Companies\Profile;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEmployeesComponent extends Component
{
    use WithPagination;
    public $company;
    public $searchQuery;
    public $showActions = false;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    public function render()
    {
        $employees = (new EmployeeService)
            ->search(
                $this->company,
                $this->searchQuery,
                'active',
                null,
                config('bondeed.frontend.dashboards.limit')
            );
        return view('livewire.companies.profile.company-profile-employees-component', [
            'employees' => $employees
        ]);
    }

    public function mount()
    {
        if(\Auth::user() && \Auth::user()->type === 'company' && $this->company === \Auth::user()->company->id) {
            $this->showActions = true;
        }
    }
}
