<?php

namespace App\Http\Livewire\Companies\Profile\Edit;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyProfileEditEmployeesComponent extends Component
{
    use WithPagination;

    public $company;
    public $searchQuery;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['companyProfileEditEmployeesComponent' => '$refresh'];

    public function render()
    {
        $employees = EmployeeService::getEmployeesByCompany($this->company, config('bondeed.frontend.dashboards.limit'),
            $this->searchQuery, 'active');
        return view('livewire.companies.profile.edit.company-profile-edit-employees-component', [
            'employees' => $employees
        ]);
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }
}
