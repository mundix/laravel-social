<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Employees;

use App\Models\Employee;
use App\Models\User;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditEmployeeLoopComponent extends Component
{
    use SupportUiNotification;
    public $employee;
    public $company;

    protected $listeners = [
        'disableEmployee' => 'doDisable',
        'companyProfileEditEmployeeLoopComponent' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.companies.profile.edit.employees.company-profile-edit-employee-loop-component');
    }

    public function disable($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to disable this employee ?' ,
            'confirmButtonText' => 'Yes',
            'method' => 'disableEmployee',
            'params' => $id
        ]);
    }

    public function doDisable($id)
    {
        $obj = Employee::find($id);
        $user = User::where('id', $obj->user_id)->first();
        $user->update(['status' => 'disabled']);
        $this->alert()->success(['title' => 'Employee was disabled']);
        $this->emit('companyProfileEditEmployeeLoopComponent');
        $this->emit('companyProfileEditEmployeesComponent');
    }

    public function activate($id)
    {
        $obj = Employee::find($id);
        $user = User::where('id', $obj->user_id)->first();
        $user->update(['status' => 'active']);
        $this->alert()->success(['title' => 'Employee was activated']);
        $this->emit('companyProfileEditEmployeeLoopComponent');
        $this->emit('companyProfileEditEmployeesComponent');
    }
}
