<?php

namespace App\Http\Livewire\Companies\Employees;

use App\Models\CompanyInvite;
use App\Models\Employee;
use App\Models\User;
use App\Models\UserToken;
use App\Services\GlobalService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;

class CompanyCreateSingleEmployeeModalComponent extends Component
{
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $company;
    public $first_name;
    public $last_name;
    public $email;

    public $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email:rfc,dns|unique:users',
    ];

    public function render()
    {
        return view('livewire.companies.employees.company-create-single-employee-modal-component');
    }

    public function save()
    {
        $validator = \ Validator::make([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
        ], $this->rules);

        if($validator->fails()) {
            $message = $this->getErrorFromValidator($validator, true);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }
        $data = [
                'type' => 'employee',
                'status_id' => 3,
                'confirmed' => 'pending',
                'accept_agreements' => true,
                'email' => $this->email,
                'password' => \Hash::make(\Str::random(9))
        ];



        $user = User::create($data);
        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
        $employee = Employee::create($data);
        $user->employee()->save($employee);


        $token = GlobalService::generateToken();
        $data = [
            'employee_id' => $user->employee->id,
            'company_id' => $this->company->id
        ];
        $employee->user->user_token()->save(UserToken::create(['token' => $token]));

        $companyInvite = CompanyInvite::create($data);
        $this->company->invites()->save($companyInvite);

        $this->company->employees()->attach($employee->id);

        $this->resetInputs();
        $this->alert()->success(['title' => 'Your invitation has been sent.']);
        $this->emit('closeModals');
        $this->emit('openEmployeeSuccess');

    }

    public function resetInputs()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
    }
}
