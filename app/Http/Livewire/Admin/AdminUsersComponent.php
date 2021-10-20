<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;

class AdminUsersComponent extends Component
{

    use WithPagination;

    public $searchQuery;
    public $status;
    public $type = null;
    public $disabled = null;
    public $sortField = 'admins.first_name';
    public $sortAsc = true;

    protected $listeners = ['adminCompaniesComponent' => 'render'];

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function render()
    {
        $admins = (new UserService)
            ->searchAdmins(
                $this->searchQuery,
                $this->status,
                $this->disabled,
                config('bondeed.frontend.dashboards.limit-10'),
                $this->sortField,
                $this->sortAsc
            );

        return view('livewire.admin.admin-users-component', [
            'admins' => $admins,
            'totalUsers' => $admins->count()
        ]);
    }

    public function hydrate()
    {
        $this->emit('updateDOM');
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
