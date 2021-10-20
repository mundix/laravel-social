<?php

namespace App\Http\Livewire\Companies\Auth;

use App\Services\StatusService;
use Livewire\Component;

class CompanyLoginComponent extends Component
{
    public $email;
    public $password;
    public $remember = false;

    protected $rules = [
        'password' => 'required',
        'email' => 'required|email:rfc,dns',
    ];

    public function render()
    {
        return view('livewire.companies.auth.company-login-component')->layout('layouts.base');
    }

    public function mount()
    {
        if (auth()->check()) {
            if (auth()->user()->confirmed === StatusService::PENDING) {
                session()->flash('error', 'Your account isn\'t verified or received and invitation');
            } else {
                return redirect()->route('company.profile');
            }
        }
    }

    private function resetInputFields()
    {
        $this->email = '';
        $this->password = '';
    }

    public function submit()
    {
        $this->validate();

        if (\Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->flash('message', trans('auth.login.auth_success'));
            return redirect()->route('company.profile');
        } else {
            $this->password = '';
            session()->flash('error', trans('auth.login.auth_fail'));
        }
    }
}
