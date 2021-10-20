<?php

namespace App\Http\Livewire\Companies\Admin\Users;

use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;

class CompanyAdminUsersDashboardComponent extends Component
{
    use WithPagination;

    public $company;
    public $searchQuery;
    public $status;
    public $type = null;
    public $disabled = null;
    public $sortField = 'admins.first_name';
    public $sortAsc = true;

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'companyAdminUsersDashboardComponent' => 'render',
        'refreshCompanyAdminUsersDashboardComponent' => '$refresh',
    ];

    public function render()
    {

        $admins = (new UserService)
            ->searchCompaniesAdmin(
                $this->searchQuery,
                $this->status,
                $this->disabled,
                config('bondeed.frontend.dashboards.limit-10'),
                $this->sortField,
                $this->sortAsc
            );

        return view('livewire.companies.admin.users.company-admin-users-dashboard-component', [
            'admins' => $admins,
            'totalUsers' => $admins->count()
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
