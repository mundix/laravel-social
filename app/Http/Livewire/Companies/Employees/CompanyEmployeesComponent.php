<?php

namespace App\Http\Livewire\Companies\Employees;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyEmployeesComponent extends Component
{
    use WithPagination;

    public $company;
    public $searchQuery;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
    ];

    public function render()
    {
        return view('livewire.companies.employees.company-employees-component', [
            'employees' => EmployeeService::getEmployeesByCompany($this->company, 6, $this->searchQuery)
        ]);
    }
}
