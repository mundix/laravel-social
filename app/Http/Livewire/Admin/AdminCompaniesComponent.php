<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use App\Services\CauseService;
use App\Services\CompanyService;
use App\Services\UserService;
use App\Traits\SupportUiNotification;
use Livewire\Component;
use Livewire\WithPagination;

class AdminCompaniesComponent extends Component
{
    use WithPagination;
    use SupportUiNotification;

    public $searchQuery;
    public $status;
    public $disabled = null;
    public $sortField = 'companies.name';
    public $sortAsc = true;

    protected $listeners = [
        'adminCompaniesComponent' => '$refresh' ,
        'renderAdminCompaniesComponent' => 'render' ,
        'updateAdminCompaniesComponent' => 'isUpdated'
    ];

    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'status' => 'all',
    ];

    public function render()
    {
        $users = (new UserService)
            ->searchCompanies(
                $this->searchQuery,
                $this->status,
                $this->disabled,
                config('bondeed.frontend.dashboards.limit-10'),
                $this->sortField,
                $this->sortAsc
            );
        return view('livewire.admin.admin-companies-component', [
            'users' => $users
        ]);
    }

    public function hydrate()
    {
        $this->emit('listComponentUpdated');
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {

            $this->sortAsc = !$this->sortAsc;

        } else {

            $this->sortAsc = true;

        }

        $this->sortField = $field;

    }

    public function updatedStatus($value)
    {
        $this->status = $value;

        $this->emit('listComponentUpdated');

    }

    public function paginationView()
    {
        return 'vendor.livewire.custom-pagination-foundation';
    }

    public function activate($id)
    {
        $user = User::find($id);
        $user->update(['status' => 'active']);
        $this->alert()->success(['title' => 'This company was activated']);
        $this->emit('updateDOM');
    }

    public function toggleStatus($id)
    {
        $user = User::find($id);
        $status = ($user->status === 'active') ? 'disabled' : 'active';
        $this->alert()->success(['title' => 'This company was ' . $status]);
        $user->update(['status' => $status]);
        $this->emit('updateDOM');
    }
}
