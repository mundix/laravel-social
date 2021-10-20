<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Admin;
use App\Models\User;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminInviteUserModalComponent extends Component
{
    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    public $profile;
    public $loading = false;
    public $first_name;
    public $last_name;
    public $phone;
    public $email;

    protected $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email:rfc,dnsunique:users',
        'phone' => 'numeric|min:9|digits_between:9,11',
    ];

    public function render()
    {
        return view('livewire.admin.users.admin-invite-user-modal-component');
    }

    public function invite()
    {
        $this->loading = true;
        $validator = \Validator::make([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
        ], $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);
            $this->alert()->error(['title' => $message]);
            $validator->validate();
        }

        $user = User::create([
            'email' => $this->email,
            'type' => 'admin',
            'password' => \Str::random(8),
        ]);

        $data = [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'user_id' => $user->id,
        ];
        $user->admin()->save(Admin::create($data));
        $this->resetInputs();
        $this->loading = false;
        $this->emit('successModal');
        session()->flash('notification_title' ,'Admin invitation was send');
        return redirect()->route('admin.users');

    }

    public function resetInputs()
    {
        $this->first_name = '';
        $this->last_name = '';
        $this->phone = '';
        $this->email = '';
    }
}
