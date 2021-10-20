<?php

namespace App\Http\Livewire\Users\Auth;

use App\Services\EmployeeService;
use App\Services\StatusService;
use Livewire\Component;

class LoginComponent extends Component
{
    public $email, $password, $remember = false;

    public function render()
    {
        return view( 'livewire.users.auth.login')->layout( 'layouts.base');
    }

    public function mount()
    {
        if(auth()->check() )
        {
            if(auth()->user()->confirmed === StatusService::PENDING)
            {
                session()->flash( 'error', 'Your account isn\'t verified or received and invitation');
            }else
                return redirect()->route( 'users.profile');
        }
    }

    private function resetInputFields()
    {
        $this->email = '';
        $this->password = '';
    }

    public function doLogin()
    {
        $validatedDate = $this->validate([
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        if(\Auth::attempt([ 'email' => $this->email, 'password' => $this->password], $this->remember))
        {
            session()->flash( 'message', trans( 'auth.login.auth_success'));
            return redirect()->route( 'users.profile');
        }else{
            $this->password = '';
            session()->flash( 'error', trans( 'auth.login.auth_fail'));
        }
    }
}
