<?php

namespace App\Http\Livewire\Admin\Users;

use App\Traits\SupportUiNotification;
use Livewire\Component;

class AdminUserLoopComponent extends Component
{
    use SupportUiNotification;
    public $user;
    public $status;
    public $admin;

    public function render()
    {
        return view('livewire.admin.users.admin-user-loop-component');
    }

    public function mount()
    {
        $this->status = $this->admin->user->status;
    }

    public function activate()
    {
        $this->admin->user->update(['status' => 'active']);
        $this->status = 'active';
    }

    public function toggleStatus()
    {
        $status = ($this->user->status === 'active') ? 'disabled' : 'active';
        $this->user->update(['status' => $status]);
        $this->status = $status;
        $status = ($this->user->status === 'active') ? 'Enabled' : 'Disabled';
        $message = 'User status was updated to ' . $status;
        $this->alert()->success(['title' => $message]);
    }
}
