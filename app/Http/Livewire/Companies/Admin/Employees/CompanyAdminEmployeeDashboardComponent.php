<?php

namespace App\Http\Livewire\Companies\Admin\Employees;

use App\Services\EmployeeService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyAdminEmployeeDashboardComponent extends Component
{
    use WithPagination;

    public $company;
    public $searchQuery;
    public $status;
    public $disabled = null;
    public $sortField = 'first_name';
    public $sortAsc = true;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['companyProfileEditEmployeesComponent' => '$refresh'];

    public function render()
    {
        $employees = (new EmployeeService)
            ->search(
                $this->company,
                $this->searchQuery,
                $this->status,
                $this->disabled,
                config('bondeed.frontend.dashboards.limit-10'),
                $this->sortField,
                $this->sortAsc,
            );

        return view('livewire.companies.admin.employees.company-admin-employee-dashboard-component', [
            'employees' => $employees,
            'totalEmployees' => $employees->count()
        ]);
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
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
}
