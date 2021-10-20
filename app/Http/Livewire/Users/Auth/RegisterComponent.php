<?php

namespace App\Http\Livewire\Users\Auth;

use App\Models\UserStatus;
use App\Services\EmployeeService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;


class RegisterComponent extends Component
{
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $first_name;
    public $last_name;
    public $email;
    public $password;
    public $user;
    public $nextStep = false;

    public $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'password' => 'required|min:8',
    ];

    public function render()
    {
        return view( 'livewire.users.auth.register')->layout( 'layouts.base', [ 'bodyClass' => 'User UserSignup']);
    }

    public function mount()
    {
        $user = EmployeeService::getUser();
        $this->user = $user;
        $this->first_name = $user->employee->first_name;
        $this->last_name = $user->employee->last_name;
        $this->email = $user->email;
    }

    public function doRegister()
    {
        $validator = \Validator::make([
            'first_name' => $this->first_name,
            'password' => $this->password,
            'last_name' => $this->last_name,
        ], $this->rules);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }
        $this->user->update([
            'password' => Hash::make($this->password),
            'accept_agreements' => true,
        ]);
        $employee = $this->user->employee;
        $employee->update(['first_name' => $this->first_name,'last_name' => $this->last_name]);

        $this->alert()->success(['title' => trans('auth.register.auth_success')]);
        $this->nextStep = true;
    }
}
