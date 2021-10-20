<?php

namespace App\Http\Livewire\Admin\Companies;

use App\Models\Invite;
use App\Services\GlobalService;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;

class AdminInviteCompanyComponent extends Component
{

    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $name;
    public $email;
    public $token;
    public $loading = false;

    public function render()
    {
        return view('livewire.admin.companies.admin-invite-company-component');
    }

    protected $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email:rfc,dns|unique:users,email',
        'token' => 'required|unique:invites,token',
    ];

    public function invite()
    {
        $token = GlobalService::generateToken();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'token' => $token
        ];

        $validator = \Validator::make($data, $this->rules);

        $data['token'] = $token;

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        Invite::create($data);

        $this->alert()->success(['title' => 'Your invitation has been sent.']);

        $this->emit('successModal');

        $this->emit('adminCompaniesComponent');

        session()->flash('notification_title', 'Your invitation has been sent.');

        return redirect()->route('admin.companies');
    }

    public function updatedEmail($value)
    {
        $this->validate([
            'email' => 'required|email:rfc,dns|unique:users,email||unique:invites,email'
        ]);
    }
}
