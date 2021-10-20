<?php

namespace App\Http\Livewire\Admin\Companies;

use App\Models\User;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;

class AdminCompanyLoopComponent extends Component
{
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $company;
    public $status;

    protected $listeners = [
        'renderAdminCompanyLoopComponent' => 'render',
        'refreshAdminCompanyLoopComponent' => '$refresh',
        'activateUser' => 'doActivate',
        'disableUser' => 'doDisable'
    ];

    public function render()
    {
        return view('livewire.admin.companies.admin-company-loop-component');
    }

    public function mount()
    {
        $this->status = $this->company->user->status;
    }

    public function setActivate($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to enable this Company?',
            'confirmButtonText' => 'Enable',
            'method' => 'activateUser',
            'params' => $id
        ]);
    }

    public function setDisable($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to disable this Company?',
            'confirmButtonText' => 'Disable',
            'method' => 'disableUser',
            'params' => $id
        ]);
    }

    public function doActivate($id)
    {
        $obj = User::find($id);
        if ($obj) {
            $obj->update(['status' => 'active']);
            $this->alert()->success(['title' => 'Company enabled']);
            $this->status = 'active';
        }
        $this->emit('renderAdminCompanyLoopComponent');
        $this->emit('refreshAdminCompanyLoopComponent');
        $this->emit('adminCompaniesComponent');
        $this->emit('renderAdminCompaniesComponent');
    }

    public function doDisable($id)
    {
        $obj = User::find($id);
        if ($obj) {
            $obj->update(['status' => 'disabled']);
            $this->alert()->success(['title' => 'Company disabled']);
            $this->status = 'disabled';
        }
        $this->emit('renderAdminCompanyLoopComponent');
        $this->emit('refreshAdminCompanyLoopComponent');
        $this->emit('adminCompaniesComponent');
        $this->emit('renderAdminCompaniesComponent');
    }
}
