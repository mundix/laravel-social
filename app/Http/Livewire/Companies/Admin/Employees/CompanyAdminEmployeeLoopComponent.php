<?php

namespace App\Http\Livewire\Companies\Admin\Employees;

use App\Mail\Admin\AdminInviteEmail;
use App\Mail\CompanyInviteMail;
use App\Models\CompanyInvite;
use App\Models\Employee;
use App\Models\Invite;
use App\Models\User;
use App\Models\UserToken;
use App\Services\GlobalService;
use App\Traits\SupportUiNotification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CompanyAdminEmployeeLoopComponent extends Component
{
    use SupportUiNotification;

    public $employee;
    public $company;
    public $status;

    protected $listeners = [
        'disableEmployee' => 'doDisable',
        'companyProfileEditEmployeeLoopComponent' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.companies.admin.employees.company-admin-employee-loop-component');
    }

    public function mount()
    {
        $this->status = $this->employee->user->status;
    }

    public function disable()
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to disable this employee ?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'disableEmployee',
        ]);
    }

    public function doDisable()
    {
        $this->employee->user->update(['status' => 'disabled']);
        $this->status = $this->employee->user->status;
        $this->alert()->success(['title' => 'Your Employee has been disabled']);
        $this->emit('companyProfileEditEmployeeLoopComponent');
        $this->emit('companyProfileEditEmployeesComponent');
    }

    public function activate()
    {
        $this->employee->user->update(['status' => 'active']);
        $this->status = $this->employee->user->status;
        $this->alert()->success(['title' => 'Your Employee has been enabled']);
        $this->emit('companyProfileEditEmployeeLoopComponent');
        $this->emit('companyProfileEditEmployeesComponent');
    }

    public function reInvite()
    {
        $token = GlobalService::generateToken();
        $data = [
            'employee_id' => $this->employee->id,
            'company_id' => $this->company->id
        ];
        $userToken = UserToken::where('user_id', $this->employee->user->id)->first();
        $userToken->update(['token' => $token]);

        $companyInvite = CompanyInvite::where('employee_id', $this->employee->id)->first();
        $companyInvite->update(['status' => 'pending']);

        try {
            Mail::to($this->employee->user->email)->send(new CompanyInviteMail($companyInvite));
        }catch (\Exception $e){
            \Log::info('Email wasn\'t able to send ' . $e->getMessage());
        }

        $this->alert()->success(['title' => 'This Employee was re-invited']);
    }
}
