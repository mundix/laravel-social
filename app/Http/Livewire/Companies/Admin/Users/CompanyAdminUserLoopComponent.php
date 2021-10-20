<?php

namespace App\Http\Livewire\Companies\Admin\Users;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminUserLoopComponent extends Component
{
    use SupportUiNotification;

    public $admin;
    public $user;
    public $status;

    public function render()
    {
        return view('livewire.companies.admin.users.company-admin-user-loop-component');
    }

    public function mount()
    {
        $this->user = $this->admin->user;
        $this->status = $this->user->status;
    }

    public function toggleStatus($status = 'active')
    {
        $this->user->update(['status' => $status]);
        $this->status = $status;
        $this->alert()->success(['title' => 'This user was ' . $this->user->statusLabel]);
        $this->emit('companyAdminUsersDashboardComponent');
        $this->emit('refreshCompanyAdminUsersDashboardComponent');
    }
}
