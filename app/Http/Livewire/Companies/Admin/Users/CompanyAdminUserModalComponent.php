<?php

namespace App\Http\Livewire\Companies\Admin\Users;

use App\Models\Admin;
use App\Models\CompanyAdmin;
use App\Models\User;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyAdminUserModalComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $company = null;
    public $firstName;
    public $lastName;
    public $email;
    public $type = null;
    public $phone;

    protected $rules = [
        'firstName' => 'required',
        'lastName' => 'required',
        'email' => 'required|email:rfc,dns|unique:users,email',
        'phone' => 'numeric|min:9|digits_between:9,11',
    ];

    public function render()
    {
        return view('livewire.companies.admin.users.company-admin-user-modal-component');
    }

    public function invite()
    {
        if(is_null($this->type)) {
            $this->alert()->error(['title' => 'User type cannot be null.']);
            return;
        }
        $this->loading = true;
        $validator = \Validator::make([
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
        ], $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator, true);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $user = User::create([
            'email' => $this->email,
            'type' => $this->type ?? 'user',
            'password' => \Str::random(8),
        ]);

        $data = [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'phone' => $this->phone,
            'user_id' => $user->id,
        ];

        $user->admin()->save(Admin::create($data));
        if($this->type === 'company-admin') {
             CompanyAdmin::create(['user_id'=> $user->id, 'company_id' => $this->company->id]);
        }
        $this->resetInputs();
        $this->loading = false;
        $this->alert()->success(['title' => 'You have invited this Admin successfully.']);
        $this->emit('successModal');
        $this->emit('closeModals');
        $this->emit('companyAdminUsersDashboardComponent');

    }

    public function resetInputs()
    {
        $this->firstName = '';
        $this->lastName = '';
        $this->phone = '';
        $this->email = '';
    }
}
