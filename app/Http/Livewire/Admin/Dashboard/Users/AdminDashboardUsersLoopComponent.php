<?php

namespace App\Http\Livewire\Admin\Dashboard\Users;

use Livewire\Component;

class AdminDashboardUsersLoopComponent extends Component
{
    public $user;
    public $status = false;
    public function render()
    {
        return view('livewire.admin.dashboard.users.admin-dashboard-users-loop-component');
    }

    public function mount()
    {
        $this->disabled = $this->user->employee->status === 'enabled' ? true : false;
    }

    public function toggleStatus()
    {
        $user = $this->user;
        $employee = $user->employee;
        $status = ($employee->status === 'enabled') ? 'disabled' : 'enabled';
        $employee->status = $status;
        $this->status = $status;
        $employee->save();
    }
}
