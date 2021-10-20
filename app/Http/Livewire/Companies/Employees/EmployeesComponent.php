<?php

namespace App\Http\Livewire\Companies\Employees;

use App\Services\CompanyService;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeesComponent extends Component
{
    use WithPagination;

    public $user;
    public $company;
    public $searchQuery = '';
    public $pageName = 'employeesPage';

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $employees = $this->company->employees()
            ->where( function ($query)  {
                $query->when(!empty($this->searchQuery), function ($query)  {
                    $query->where('first_name', 'like', '%' . $this->searchQuery . '%');
                    $query->orWhere('last_name', 'like', '%' . $this->searchQuery . '%');
                    $query->orWhere('description', 'like', '%' . $this->searchQuery . '%');
                    $query->orWhere('location', 'like', '%' . $this->searchQuery . '%');
                });
            })->whereHas('user', function($query) {
                $query->where('status', 'active');
                $query->where('confirmed', 'approved');
            })
            ->paginate(config('bondeed.frontend.dashboards.limit') + 1, ['*'], $this->pageName);

        return view('livewire.companies.employees.employees-component',
            [
                'employees' =>  $employees
            ]);
    }

    public function mount($company, $user)
    {
        $this->company = $company;
    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }
}
