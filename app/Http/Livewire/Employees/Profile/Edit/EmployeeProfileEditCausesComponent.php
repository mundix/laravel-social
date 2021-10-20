<?php

namespace App\Http\Livewire\Employees\Profile\Edit;

use App\Models\CategoryCause;
use App\Services\CauseService;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeProfileEditCausesComponent extends Component
{

    use WithPagination;

    public $categories;
    public $company;
    public $employee;
    public $searchQuery = '';
    public $category = 'all';
    public $sortField = 'name';
    public $sortAsc = true;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $causes = (new CauseService)->getFavoriteCausesByUser(
            $this->employee->user,
            config('bondeed.frontend.dashboards.limit'),
            $this->searchQuery,
            $this->category,
            $this->sortField,
            $this->sortAsc,
        );

        return view('livewire.employees.profile.edit.employee-profile-edit-causes-component', [
            'causes' => $causes,
        ]);
    }

    public function mount()
    {
        $this->categories = CategoryCause::all();
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
